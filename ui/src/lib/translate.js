import {de, en} from 'json-loader!yaml-loader!@/resources/strings.yml'

const missingTranslations = []

export const translate = key => {
  const language = navigator.language.split('-')[0]
  let keyset = null

  switch (language) {
    case 'en':
      keyset = en
      break
    case 'de':
      keyset = de
      break
    default:
      console.debug(`Unsupported language: ${language}`)
      keyset = de
      break
  }

  // eslint-disable-next-line no-prototype-builtins
  if (!keyset.hasOwnProperty(key) && !missingTranslations.includes(key)) {
    missingTranslations.push(key)
    console.debug(`Missing translation for locale [${language}]: ${key}`)
  }

  return keyset[key] || de[key] || key
}
