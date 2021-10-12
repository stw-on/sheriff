import {default as BaseAxios} from 'axios'

const protocol = process.env.NODE_ENV === 'development'
  ? window.location.protocol
  : 'https:'

export const apiBaseUrl = `${protocol}//${window.location.host}/api/v1`

const axios = BaseAxios.create({
  baseURL: apiBaseUrl,
})

for (const method of ['request', 'delete', 'get', 'head', 'options', 'post', 'put', 'patch']) {
  axios['$' + method] = function () {
    return this[method]
      .apply(this, arguments)
      .then(res => res && res.data)
  }
}

export {axios}

export const axiosForHost = host => BaseAxios.create({
  baseURL: `${protocol}//${host}/api/v1`,
})
