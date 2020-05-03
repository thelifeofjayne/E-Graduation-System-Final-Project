import { TYPE } from '../../config'

const _TYPE = TYPE.PRINTING.REVIEW

const init = {
    reviews: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_ALL_REVIEW:
            return {
                ...state,
                reviews: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
