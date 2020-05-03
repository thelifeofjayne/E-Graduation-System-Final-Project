import {URL, TYPE} from "../../config"
import 'whatwg-fetch'

const _URL = URL.PRINTING.PRINTING
const _TYPE = TYPE.PRINTING.PRINTING

export function getPrinting() {
    return function (dispatch) {
        fetch(_URL.GET_PRINTING, {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_PRINTING,
                payload: json
            })
        })
    }
}