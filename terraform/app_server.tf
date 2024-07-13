resource "tls_private_key" "web" {
  algorithm = "ED25519"
}

resource "aws_key_pair" "web" {
  key_name   = "web-keypair"
  public_key = tls_private_key.web.public_key_openssh
}

resource "aws_security_group" "web" {
  name   = "web-sg"
  vpc_id = aws_vpc.main.id

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }
  ingress {
    from_port       = 80
    to_port         = 80
    protocol        = "tcp"
    security_groups = [aws_security_group.alb.id]
  }
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_instance" "web" {
  ami                         = "ami-03350e4f182961c7f"
  instance_type               = "t2.micro"
  subnet_id                   = aws_subnet.public_1a.id
  associate_public_ip_address = true

  vpc_security_group_ids = [aws_security_group.web.id]

  iam_instance_profile = aws_iam_instance_profile.web.name

  key_name = aws_key_pair.web.key_name

  user_data = templatefile("startup.sh.template", {
    app_url               = "https://${var.domain_name}"
    db_host               = aws_db_instance.mysql.address
    db_secret             = aws_db_instance.mysql.master_user_secret[0].secret_arn
    aws_access_key_id     = var.aws_access_key_id
    aws_secret_access_key = var.aws_secret_access_key
    aws_bucket            = aws_s3_bucket.web_storage.bucket
    stripe_key            = var.stripe_key
    stripe_secret         = var.stripe_secret
    stripe_webhook_secret = var.stripe_webhook_secret
    nginx_conf            = file("nginx.conf")
    laravel_env           = file(".env.example")
  })

  tags = {
    Name = "web-server"
  }
}

output "server_ip" {
  value = aws_instance.web.public_ip
}

output "ssh_private_key" {
  value     = tls_private_key.web.private_key_pem
  sensitive = true
}
