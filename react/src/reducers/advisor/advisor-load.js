import { TYPE } from '../../config'

const _TYPE = TYPE.ADVISOR.ADVISOR_LOAD

const init = {
    advisors: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_ADVISOR:
            return {
                ...state,
                advisors: action.payload
            }
    }
    return state
}
