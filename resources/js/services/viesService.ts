import axios from 'axios'

export async function viesCheck(country: string, nif: string) {
  const { data } = await axios.get(`/api/vies/${country}/${nif}`)
  return data
}
