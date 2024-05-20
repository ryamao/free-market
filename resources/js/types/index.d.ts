export interface UserData {
  id: number
  name: string
  image_url: string
}

export interface Item {
  id: number
  seller: UserData
  name: string
  price: number
  image_url: string
  description: string
  categories: string[]
  condition: string
  favorite_count: number
  is_favorite: boolean
  comment_count: number
  created_at: string
}

export interface Comment {
  id: number
  user: UserData
  content: string
  created_at: string
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
