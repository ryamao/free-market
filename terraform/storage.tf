resource "aws_s3_bucket" "web_storage" {
  bucket_prefix = "web-storage"
  force_destroy = true
}

resource "aws_s3_bucket_ownership_controls" "web_storage" {
  bucket = aws_s3_bucket.web_storage.id
  rule {
    object_ownership = "BucketOwnerPreferred"
  }
}

resource "aws_s3_bucket_public_access_block" "web_storage" {
  bucket                  = aws_s3_bucket.web_storage.id
  block_public_acls       = false
  block_public_policy     = false
  ignore_public_acls      = false
  restrict_public_buckets = false
}

resource "aws_s3_bucket_acl" "web_storage" {
  depends_on = [
    aws_s3_bucket_ownership_controls.web_storage,
    aws_s3_bucket_public_access_block.web_storage
  ]
  bucket = aws_s3_bucket.web_storage.id
  acl    = "public-read"
}

resource "aws_s3_bucket_policy" "web_storage" {
  bucket = aws_s3_bucket.web_storage.id
  policy = jsonencode({
    Version = "2012-10-17",
    Statement = [
      {
        Effect    = "Allow",
        Principal = "*",
        Action    = "s3:GetObject",
        Resource  = "${aws_s3_bucket.web_storage.arn}/*",
      },
    ],
  })
  depends_on = [aws_s3_bucket_acl.web_storage]
}
