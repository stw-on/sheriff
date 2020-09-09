import {default as BaseAxios} from 'axios'

const protocol = process.env.NODE_ENV === 'development'
  ? window.location.protocol
  : 'https:'

export const apiBaseUrl = `${protocol}//${window.location.host}/api/v1`

export const axios = BaseAxios.create({
  baseURL: apiBaseUrl,
})

export const axiosForHost = host => BaseAxios.create({
  baseURL: `${protocol}//${host}/api/v1`,
})
