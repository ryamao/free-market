data "aws_route53_zone" "ap" {
  name = var.domain_name
}

resource "aws_route53_record" "ap" {
  zone_id = data.aws_route53_zone.ap.zone_id
  name    = data.aws_route53_zone.ap.name
  type    = "A"

  alias {
    name                   = aws_lb.web.dns_name
    zone_id                = aws_lb.web.zone_id
    evaluate_target_health = true
  }
}

resource "aws_route53_record" "ap_certificate" {
  for_each = {
    for dvo in aws_acm_certificate.ap.domain_validation_options : dvo.domain_name => {
      name   = dvo.resource_record_name
      type   = dvo.resource_record_type
      record = dvo.resource_record_value
    }
  }

  zone_id         = data.aws_route53_zone.ap.zone_id
  ttl             = 60
  name            = each.value.name
  type            = each.value.type
  records         = [each.value.record]
  allow_overwrite = true
}

resource "aws_acm_certificate" "ap" {
  domain_name       = aws_route53_record.ap.name
  validation_method = "DNS"

  lifecycle {
    create_before_destroy = true
  }
}

resource "aws_acm_certificate_validation" "ap" {
  certificate_arn         = aws_acm_certificate.ap.arn
  validation_record_fqdns = [for record in aws_route53_record.ap_certificate : record.fqdn]
}
