import {TYPE} from '../../config'

const _TYPE = TYPE.EVALUATION.EVALUATION_LIST

const init = {
    evaluations: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_EVALUATION:
            return {
                ...state,
                evaluations: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}