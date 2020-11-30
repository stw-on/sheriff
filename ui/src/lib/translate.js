import bundledStrings from 'json-loader!yaml-loader!@/resources/strings.yml'
import merge from 'lodash.merge'

const missingTranslations = []

export const translate = key => {
  const language = navigator.language.split('-')[0]
  let keyset = null
  const strings = merge(bundledStrings, window.__sheriff_config?.custom_translations ?? {})

  switch (language) {
    case 'en':
      keyset = strings.en
      break
    case 'de':
      keyset = strings.de
      break
    default:
      console.debug(`Unsupported language: ${language}`)
      keyset = strings.de
      break
  }

  // eslint-disable-next-line no-prototype-builtins
  if (!keyset.hasOwnProperty(key) && !missingTranslations.includes(key)) {
    missingTranslations.push(key)
    console.debug(`Missing translation for locale [${language}]: ${key}`)
  }

  return keyset[key] || strings.de[key] || key
}
