import {default as BaseAxios} from 'axios'
import axiosRetry from 'axios-retry'

const protocol = process.env.NODE_ENV === 'development'
  ? window.location.protocol
  : 'https:'

export const apiBaseUrl = `${protocol}//${window.location.host}/api/v1`

export const makeAxiosInstanceRetryRequests = instance => {
  axiosRetry(instance, {
    retries: 3,
    retryDelay: axiosRetry.exponentialDelay,
    retryCondition: axiosRetry.isNetworkOrIdempotentRequestError,
  })
}

const axios = BaseAxios.create({
  baseURL: apiBaseUrl,
})
makeAxiosInstanceRetryRequests(axios)

for (const method of ['request', 'delete', 'get', 'head', 'options', 'post', 'put', 'patch']) {
  axios['$' + method] = function () {
    return this[method]
      .apply(this, arguments)
      .then(res => res && res.data)
  }
}

export {axios}

export const axiosForHost = host => {
  const instance = BaseAxios.create({
    baseURL: `${protocol}//${host}/api/v1`,
  })
  makeAxiosInstanceRetryRequests(instance)
  return instance
}
