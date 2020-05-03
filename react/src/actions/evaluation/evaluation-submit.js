import {TYPE, URL} from "../../config"
import 'whatwg-fetch'

const _URL = URL.EVALUATION.EVALUATION_SUBMIT
const _TYPE = TYPE.EVALUATION.EVALUATION_SUBMIT

export function getEvaluation() {
    return function (dispatch) {
        fetch(_URL.GET_EVALUATION, {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_EVALUATION,
                payload: json
            })
        })
    }
}

export function updateEvaluation(evaluation) {
    return function (dispatch) {
        dispatch({
            type: _TYPE.SET_EVALUATION,
            payload: evaluation
        })
    }
}

export function getSubmittied() {
    return function (dispatch) {
        fetch(_URL.GET_SUBMITTED, {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_SUBMITTED,
                payload: json
            })
        })
    }
}

export function insertUserEvaluation(calendarItem, evaluation) {
    return function (dispatch) {
        const data = new FormData()
        data.append('json', JSON.stringify({
            evaluation,
            calendar_item: {
                calendar_id: calendarItem.calendar.calendar_id,
                action_id: calendarItem.action.action_id,
                level_id: calendarItem.level.level_id,
                semester_id: calendarItem.semester.semester_id,
                owner_id: calendarItem.owner_id,
            }
        }))
        fetch(_URL.INSERT, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            window.location = URL.REQUEST.REQUEST_LIST.MAIN.LINK
        })
    }
}