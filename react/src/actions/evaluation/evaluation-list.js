import {TYPE, URL} from "../../config"
import 'whatwg-fetch'

const _URL = URL.EVALUATION.EVALUATION_LIST
const _TYPE = TYPE.EVALUATION.EVALUATION_LIST

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

export function activeEvaluation(id) {
    return function (dispatch) {
        fetch(_URL.ACTIVE_EVALUATION(id), {
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

export function deleteEvaluation(evaluations, id) {
    return function (dispatch) {
        fetch(_URL.DELETE_EVALUATION(id), {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            const _evaluations = evaluations.filter(evaluation => evaluation.evaluation_id !== id)
            console.log(_evaluations)
            dispatch({
                type: _TYPE.SET_EVALUATION,
                payload: _evaluations
            })
        })
    }
}
