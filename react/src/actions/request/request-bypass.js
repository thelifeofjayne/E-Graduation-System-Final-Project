import {URL, TYPE} from "../../config"

const _URL = URL.REQUEST.REQUEST_BYPASS
const _TYPE = TYPE.REQUEST.REQUEST_BYPASS

export function getRequestBypass(callback = null) {
    return dispatch => {
        fetch(_URL.GET_REQUEST_BYPASS, {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_REQUEST_BYPASS,
                payload: json
            })
            if (callback !== null) {
                callback()
            }
        })
    }
}

export function deleteRequestBypass(semester, action, student, callback) {
    return dispatch => {
        fetch(_URL.DELETE_REQUEST_BYPASS(semester, action, student), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch(getRequestBypass(callback))
        })
    }
}

export function insertRequestBypass(semester, action, student, callback) {
    return dispatch => {
        fetch(_URL.INSERT_REQUEST_BYPASS(semester, action, student), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch(getRequestBypass(callback))
        })
    }
}

export function getStudent() {
    return dispatch => {
        fetch(_URL.GET_STUDENT, {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_STUDENT,
                payload: json
            })
        })
    }
}