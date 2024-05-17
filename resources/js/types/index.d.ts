export interface User {
  id: number
  name: string
  email: string
  email_verified_at: string
}

export interface Item {
  id: number
  user: User
  condition: string
  name: string
  price: number
  image_url: string
  description: string
}

export interface Paginator<T> {
  data: T[]
  links: {
    first: string
    last: string
    prev: string | null
    next: string | null
  }
  meta: {
    current_page: number
    from: number
    last_page: number
    links: {
      url: number | null
      label: string
      active: boolean
    }[]
    path: string
    per_page: number
    to: number
    total: number
  }
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User
  }
}
