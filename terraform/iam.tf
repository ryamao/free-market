resource "aws_iam_role" "web" {
  name = "app-server-role"
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Effect = "Allow"
        Principal = {
          Service = "ec2.amazonaws.com"
        }
        Action = "sts:AssumeRole"
      },
    ]
  })
}

resource "aws_iam_instance_profile" "web" {
  name = aws_iam_role.web.name
  role = aws_iam_role.web.name
}

resource "aws_iam_role_policy" "secrets_manager_read_only" {
  name = "secrets-manager-policy"
  role = aws_iam_role.web.id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Effect = "Allow"
        Action = [
          "secretsmanager:GetSecretValue",
        ]
        Resource = [
          aws_db_instance.mysql.master_user_secret[0].secret_arn
        ]
      },
    ]
  })
}

resource "aws_iam_role_policy" "s3_read_write" {
  name = "s3-read-write-policy"
  role = aws_iam_role.web.id

  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      {
        Effect = "Allow"
        Action = ["s3:*"]
        Resource = [
          aws_s3_bucket.web_storage.arn,
          "${aws_s3_bucket.web_storage.arn}/*"
        ]
      },
    ]
  })
}
