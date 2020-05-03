import {URL, TYPE} from "../../config"

const _URL = URL.CALENDAR.CALENDAR_LIST
const _TYPE = TYPE.CALENDAR.CALENDAR_LIST

export function deleteCalendar(calendar_id, callback) {
    return dispatch => {
        fetch(_URL.DELETE_CALENDAR(calendar_id), {
            credentials: 'same-origin'
        }).then((response) => {
            return response.json()
        }).then(() => {
            callback()
        })
    }
}

export function setCalendar(calendars) {
    return function (dispatch) {
        dispatch({
            type: _TYPE.SET_ALL,
            payload: calendars
        })
    }
}

export function getAllCalendar(callback = null) {
    return function (dispatch) {
        fetch(_URL.GET_ALL, {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_ALL,
                payload: json
            })
            if (callback !== null)
                callback(json)
        })
    }
}

export function insertCalendar(year, callback) {
    return function (dispatch) {
        const data = new FormData()
        data.append('json', JSON.stringify({year}))
        fetch(_URL.INSERT_CALENDAR, {
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

export function setPostCalendar(post) {
    return function (dispatch) {
        dispatch({
            type: _TYPE.SET_POST,
            payload: post
        })
    }
}

export function toggleBtnAddCalendar() {
    return function (dispatch) {
        dispatch({type: _TYPE.SET_BTN_ACTIVE})
    }
}

export function resetCalendarList() {
    return function (dispatch) {
        dispatch({type: _TYPE.RESET})
    }
}
