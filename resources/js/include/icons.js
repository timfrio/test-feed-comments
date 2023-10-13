import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faComments, faAt } from '@fortawesome/free-solid-svg-icons'

library.add(faComments, faAt)

export function initIcon (Vue) {
    Vue.component('font-awesome-icon', FontAwesomeIcon)
}
