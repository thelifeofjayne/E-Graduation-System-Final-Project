import {TYPE, URL} from "../../config"
import 'whatwg-fetch'

const _URL = URL.EVALUATION.EVALUATION_ALL
const _TYPE = TYPE.EVALUATION.EVALUATION_ALL

export function getUserEvaluation(calendar, semester) {
    return function (dispatch) {
        fetch(_URL.GET_USER_EVALUATION(calendar, semester), {
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (json) {
            dispatch({
                type: _TYPE.SET_USER_EVALUATION,
                payload: json
            })
        })
    }
}

export function UpdateUserEvaluation(user_evaluation) {
    return function (dispatch) {
        dispatch({
            type: _TYPE.SET_USER_EVALUATION,
            payload: user_evaluation
        })
    }
}
