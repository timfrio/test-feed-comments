import axios from 'axios';

/**
 * @type {AxiosInstance}
 */
const api = axios.create({
    baseURL: 'http://demo.xetex.kr.ua/',
    withCredentials: true,
    headers: {
        // Authorization: `Bearer ${cfg.getToken()}`,
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
})
api.defaults.withCredentials = true

export default api
