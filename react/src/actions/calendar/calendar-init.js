import {URL, TYPE} from "../../config"

const _URL = URL.CALENDAR.CALENDAR_INIT
const _TYPE = TYPE.CALENDAR.CALENDAR_INIT

export function getCalendarItemInit() {
    return dispatch => {
        fetch(_URL.GET_CALENDAR_ITEM_INIT, {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_ALL_CALENDAR_ITEM,
                payload: json
            })
        })
    }
}

export function resetCalendarInit() {
    return dispatch => {
        dispatch({
            type: _TYPE.RESET
        })
    }
}