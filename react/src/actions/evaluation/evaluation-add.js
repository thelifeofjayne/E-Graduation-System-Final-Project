import {URL, TYPE} from "../../config"
import 'whatwg-fetch'

const _URL = URL.EVALUATION.EVALUATION_ADD
const _TYPE = TYPE.EVALUATION.EVALUATION_ADD

export function insertEval(group, file, name, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({group}))
        data.append('name', JSON.stringify({name}))
        data.append('file', file)
        fetch(_URL.INSERT, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            callback(json)
        })
    }
}