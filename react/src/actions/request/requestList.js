import {URL, TYPE} from "../../config"

const _URL = URL.REQUEST.REQUEST_LIST
const _TYPE = TYPE.REQUEST.REQUEST_LIST

export function getCalendarItemWithStatus(levelId) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify(levelId))
        fetch(_URL.GET_ALL_CALENDAR_ITEM_WITH_STATUS, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_CALENDAR_ITEM_WITH_STATUS,
                payload: json
            })
        })
    }
}

export function resetRequestList() {
    return dispatch => {
        dispatch({
            type: _TYPE.RESET
        })
    }
}
