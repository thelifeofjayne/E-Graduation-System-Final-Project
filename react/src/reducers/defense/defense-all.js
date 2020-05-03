import { TYPE } from '../../config'

const _TYPE = TYPE.DEFENSE.DEFENSE_ALL

const init = {
    defenses: null,
    actions: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_DEFENSE:
            return {
                ...state,
                defenses: action.payload
            }
        case _TYPE.SET_ACTION:
            return {
                ...state,
                actions: action.payload
            }
        case _TYPE.SET_RESET:
            return { init }
    }
    return state
}
