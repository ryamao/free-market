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

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
  auth: {
    user: User
  }
}
