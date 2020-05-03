import { TYPE } from '../../config'

const _TYPE = TYPE.EVALUATION.EVALUATION_ALL

const init = {
    userEvaluations: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_USER_EVALUATION:
            return {
                ...state,
                userEvaluations: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
