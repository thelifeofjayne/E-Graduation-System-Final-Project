import { TYPE } from '../../config'

const _TYPE = TYPE.TODO.TODO_ALL

const init = {
    todos: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_TODO:
            return {
                ...state,
                todos: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
