import {URL, TYPE} from '../config'

const _URL = URL.LANGUAGE
const _TYPE = TYPE.LANGUAGE

export function getLanguage() {
    return function (dispatch) {
        fetch(_URL.GET_DATA, {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_DATA,
                payload: require('./../language-pack/' + json + '.json')
            })
        })
    }
}