// import FeedbackModule from './app/store/FeedbackModule'

function beforeCreate (context) {
  Vue.component('VariationCrossSelling', () => import('./variationcrossselling.vue'))
}

// function beforeRender (vueApp) {
// vueApp.$store.registerModule('property', FeedbackModule)
// }

export { beforeCreate }
// export { beforeCreate, beforeRender }
