import axios, { AxiosResponse } from 'axios'

import { Item } from './types'

function addToFavorites(item: Item) {
  return axios.post(route('mylist.store', { item: item }))
}

function removeFromFavorites(item: Item) {
  return axios.delete(route('mylist.destroy', { item: item }))
}

export function toggleFavorite(item: Item): Promise<AxiosResponse<any, any>> {
  if (item.is_favorite) {
    return removeFromFavorites(item)
  } else {
    return addToFavorites(item)
  }
}
