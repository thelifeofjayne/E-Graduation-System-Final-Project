import { TYPE } from '../../config'

const _TYPE = TYPE.PRINTING.PRINTING

const init = {
    printings: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_PRINTING:
            return {
                ...state,
                printings: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
