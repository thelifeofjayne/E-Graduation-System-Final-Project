import { TYPE } from '../../config'

const _TYPE = TYPE.EVALUATION.EVALUATION_SUBMIT

const init = {
    evaluation: null,
    submitted: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_EVALUATION:
            return {
                ...state,
                evaluation: action.payload
            }
        case _TYPE.SET_SUBMITTED:
            return {
                ...state,
                submitted: action.payload
            }
        case _TYPE.SET_EVALUATION:
            return {
                ...state,
                evaluation: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
