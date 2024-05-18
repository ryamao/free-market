export interface Seller {
  id: number
  name: string
}

export interface Item {
  id: number
  seller: Seller
  name: string
  price: number
  image_url: string
  description: string
  categories: string[]
  condition: string
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
