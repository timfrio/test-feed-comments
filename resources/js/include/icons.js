import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faComments, faAt, faFileLines, faFileImage } from '@fortawesome/free-solid-svg-icons'

library.add(faComments, faAt, faFileLines, faFileImage)

export function initIcon (Vue) {
    Vue.component('font-awesome-icon', FontAwesomeIcon)
}
