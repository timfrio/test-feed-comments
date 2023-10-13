import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faComments } from '@fortawesome/free-solid-svg-icons'

library.add(faComments)

export function initIcon (Vue) {
    Vue.component('font-awesome-icon', FontAwesomeIcon)
}
