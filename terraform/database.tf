resource "aws_db_parameter_group" "mysql" {
  name   = "db-parameter-group"
  family = "mysql8.0"

  parameter {
    name  = "character_set_database"
    value = "utf8mb4"
  }
  parameter {
    name  = "character_set_server"
    value = "utf8mb4"
  }
}

resource "aws_db_option_group" "mysql" {
  name                 = "db-option-group"
  engine_name          = "mysql"
  major_engine_version = "8.0"
  option {
    option_name = "MARIADB_AUDIT_PLUGIN"
  }
}

resource "aws_db_subnet_group" "private" {
  name       = "db-subnet-group"
  subnet_ids = [aws_subnet.private_1a.id, aws_subnet.private_1c.id]
}

resource "aws_security_group" "mysql" {
  name   = "mysql-sg"
  vpc_id = aws_vpc.main.id

  ingress {
    from_port       = 3306
    to_port         = 3306
    protocol        = "tcp"
    security_groups = [aws_security_group.web.id]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_db_instance" "mysql" {
  identifier                  = "mysql"
  engine                      = "mysql"
  engine_version              = "8.0.35"
  instance_class              = "db.t3.micro"
  db_name                     = "app"
  username                    = "admin"
  manage_master_user_password = true
  storage_type                = "gp2"
  allocated_storage           = 20
  publicly_accessible         = false
  deletion_protection         = false
  skip_final_snapshot         = true
  port                        = 3306
  vpc_security_group_ids      = [aws_security_group.mysql.id]
  parameter_group_name        = aws_db_parameter_group.mysql.name
  option_group_name           = aws_db_option_group.mysql.name
  db_subnet_group_name        = aws_db_subnet_group.private.name

  tags = {
    Name = "mysql"
  }
}
