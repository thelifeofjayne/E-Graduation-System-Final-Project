import {URL, TYPE} from "../../config"
import 'whatwg-fetch'

const _URL = URL.PRINTING.REVIEW
const _TYPE = TYPE.PRINTING.REVIEW

export function getReview(post, callback) {
    return function (dispatch) {
        fetch(_URL.GET_ALL_REVIEW, {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_ALL_REVIEW,
                payload: json
            })
        })
    }
}

export function insertReview(post, callback) {
    return function (dispatch) {
        const data = new FormData()
        data.append('json', JSON.stringify(post))
        fetch(_URL.INSERT_REVIEW, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (response) {
            callback(response)
        })
    }
}