const base = '/dawae/web'
const egs_base = `${base}/egs`
export const URL = {
    BASE: base,
    EGS_BASE: egs_base,
    MAIN: {
        GET_CURRENT_USER: `${egs_base}/person/current`,
        GET_CONFIG: `${egs_base}/config`
    },
    LANGUAGE: {
        GET_DATA: `${egs_base}/language/get`
    },
    CALENDAR: {
        CALENDAR_LIST: {
            MAIN: {
                PATH: `/calendarList`,
                LINK: `${egs_base}/#/calendarList`
            },
            GET_ALL: `${egs_base}/calendar/find-all`,
            INSERT_CALENDAR: `${egs_base}/calendar/calendar-insert`,
            DELETE_CALENDAR: calendarId => {
                return `${egs_base}/calendar/delete?calendar_id=${calendarId}`
            }
        },
        CALENDAR_INIT: {
            MAIN: {
                PATH: `/calendar-init`,
                LINK: `${egs_base}/#/calendar-init`
            },
            GET_CALENDAR_ITEM_INIT: `${egs_base}/calendar-item/find-init`
        },
        CALENDAR: {
            MAIN: {
                PATH: `/calendar/:calendarId`,
                LINK: calendarId => {
                    return `${egs_base}/#/calendar/${calendarId}`
                }
            },
            GET_ALL_SEMESTER: `${egs_base}/semester/all`,
            GET_ALL_ACTION_ITEM: `${egs_base}/action-item/find`,
            GET_ALL_CALENDAR_ITEM: calendarId => {
                return `${egs_base}/calendar-item/find?calendar_id=${calendarId}`
            },
            UPDATE_CALENDAR_ITEM: `${egs_base}/calendar-item/update`,
            GET_ALL_LEVEL: `${egs_base}/level/all`,
            GET_CALENDAR: calendarId => {
                return `${egs_base}/calendar/find?calendar_id=${calendarId}`
            },
            ACTIVATE_CALENDAR: `${egs_base}/calendar/activate`,
            GET_DEFENSE: `${egs_base}/defense/find`
        }
    },
    REQUEST: {
        REQUEST_BYPASS: {
            MAIN: {
                PATH: `/request-bypass`,
                LINK: `${egs_base}/#/request-bypass`
            },
            GET_REQUEST_BYPASS: `${egs_base}/action-bypass/all`,
            GET_STUDENT: `${egs_base}/person/student`,
            INSERT_REQUEST_BYPASS: (semester, action, student) => {
                return `${egs_base}/action-bypass/insert?semester=${semester}&action=${action}&student=${student}`
            },
            DELETE_REQUEST_BYPASS: (semester, action, student) => {
                return `${egs_base}/action-bypass/delete?semester=${semester}&action=${action}&student=${student}`
            }
        },
        REQUEST_LIST: {
            MAIN: {
                PATH: `/requestList`,
                LINK: `${egs_base}/#/requestList`
            },
            GET_ALL_CALENDAR_ITEM_WITH_STATUS: `${egs_base}/calendar-item/find-with-status`,
            GET_ALL_SEMESTER: `${egs_base}/semester/find-all`
        },
        REQUEST_ADD: {
            MAIN: {
                PATH: `/request-add/:ownerId/:calendarId/:levelId/:semesterId/:actionId`,
                LINK: calendarItem => {
                    return `${egs_base}/#/request-add/${calendarItem.owner_id}/${calendarItem.calendar_id}/${calendarItem.level_id}/${calendarItem.semester_id}/${calendarItem.action.action_id}`
                }
            },
            GET_STEP: actionId => {
                return `${egs_base}/action-step/insert?action_id=${actionId}`
            },
            GET_CALENDAR_ITEM: (
                ownerId,
                calendarId,
                levelId,
                semesterId,
                actionId
            ) => {
                return `${egs_base}/calendar-item/find-one?calendar_id=${calendarId}&semester_id=${semesterId}&action_id=${actionId}&owner_id=${ownerId}&level_id=${levelId}`
            },
            GET_ALL_TEACHER: load => {
                return `${egs_base}/person/find-teacher?load=${load}`
            },
            GET_ALL_POSITION: actionId => {
                return `${egs_base}/position/find?action_id=${actionId}`
            },
            GET_ALL_ROOM: `${egs_base}/room/all`,
            INSERT_USER_REQUEST: `${egs_base}/user-request/insert`,
            GET_DEFENSE_EVENT: `${egs_base}/defense/event`,
            GET_PROJECT: `${egs_base}/project/current-user`,
            ADD_PROJECT: `${egs_base}/project/update`
        },
        REQUEST_DATA: {
            MAIN: {
                PATH: `/request`,
                LINK: `${egs_base}/#/request`
            },
            GET_STEP: actionId => {
                return `${egs_base}/action-step/process?action_id=${actionId}`
            },
            GET_ALL_USER_REQUEST: (calendar, level, semester, action) => {
                return `${egs_base}/user-request/list?calendar=${calendar}&level=${level}&semester=${semester}&action=${action}`
            },
            GET_ALL_ACTION: `${egs_base}/action/request`,
            GET_STATUS: `${egs_base}/status/all`,
            UPDATE_RESULT: `${egs_base}/defense/update-result`,
            UPDATE_REQUEST_DOCUMENT: `${egs_base}/request-document/update`,
            UPDATE_REQUEST_FEE: `${egs_base}/user-request/update-fee`,
            UPDATE_DEFENSE_DOCUMENT: `${egs_base}/defense-document/update`,
            DELETE_USER_REQUEST: `${egs_base}/user-request/delete`
        }
    },
    EVALUATION: {
        EVALUATION_ADD: {
            MAIN: {
                PATH: `/evaluation-add`,
                LINK: `${egs_base}/#/evaluation-add`
            },
            INSERT: `${egs_base}/evaluation/insert`
        },
        EVALUATION_LIST: {
            MAIN: {
                PATH: `/evaluation-list`,
                LINK: `${egs_base}/#/evaluation-list`
            },
            GET_EVALUATION: `${egs_base}/evaluation/all`,
            ACTIVE_EVALUATION: id => {
                return `${egs_base}/evaluation/active?eval_id=${id}`
            },
            DELETE_EVALUATION: id => {
                return `${egs_base}/evaluation/delete?eval_id=${id}`
            }
        },
        EVALUATION_SUBMIT: {
            MAIN: {
                PATH: `/evaluation-submit`,
                LINK: `${egs_base}/#/evaluation-submit`
            },
            GET_EVALUATION: `${egs_base}/evaluation/current`,
            GET_SUBMITTED: `${egs_base}/user-evaluation/submitted`,
            INSERT: `${egs_base}/user-evaluation/insert`
        },
        EVALUATION_ALL: {
            MAIN: {
                PATH: `/evaluation-all`,
                LINK: `${egs_base}/#/evaluation-all`
            },
            GET_USER_EVALUATION: (calendar, semester) => {
                return `${egs_base}/user-evaluation/all?calendar=${calendar}&semester=${semester}`
            }
        }
    },
    DEFENSE: {
        DEFENES_ALL: {
            MAIN: {
                PATH: '/defense',
                LINK: `${egs_base}/#/defense`
            },
            GET_DEFENSE: (calendar, level, semester, action) => {
                return `${egs_base}/defense/list?calendar=${calendar}&level=${level}&semester=${semester}&action=${action}`
            },
            GET_ACTION: `${egs_base}/action/defense`
        }
    },
    TODO: {
        TODO_ALL: {
            MAIN: {
                PATH: `/todo`,
                LINK: `${egs_base}/#/todo`
            },
            GET_TODO: `${egs_base}/todo/all`
        }
    },
    ADVISOR: {
        ADVISOR_LOAD: {
            MAIN: {
                PATH: `/advisor-load`,
                LINK: `${egs_base}/#/advisor-load`
            },
            GET_ADVISOR: `${egs_base}/advisor/load`
        }
    }
}

export const TYPE = {
    MAIN: {
        SET_HEADER: 'SET_MAIN_HEADER',
        SET_CONTAINER: 'SET_MAIN_CONTAINER',
        SET_CURRENT_USER: 'SET_MAIN_CURRENT_USER',
        SET_CONFIG: 'SET_MAIN_CONFIG'
    },
    LANGUAGE: {
        SET_DATA: 'SET_LANGUAGE_DATA'
    },
    CALENDAR: {
        CALENDAR_LIST: {
            SET_ALL: 'SET_CALENDAR_LIST_ALL',
            SET_POST: 'SET_CALENDAR_LIST_POST',
            SET_BTN_ACTIVE: 'SET_CALENDAR_LIST_ACTIVE_BTN',
            RESET: 'RESET_CALENDAR_LIST'
        },
        CALENDAR_INIT: {
            SET_ALL_CALENDAR_ITEM: 'SET_CALENDAR_INITCALENDAR_ITEM_ALL',
            RESET: 'RESET_CALENDAR_INIT'
        },
        CALENDAR: {
            SET_ALL_SEMESTER: 'SET_CALENDAR_SEMESTER_ALL',
            SET_ALL_ACTION_ITEM: 'SET_CALENDAR_ACTION_ITEM_ALL',
            SET_ALL_CALENDAR_ITEM: 'SET_CALENDAR_CALENDAR_ITEM_ALL',
            UPDATE_CALENDAR_ITEM: 'UPDATE_CALENDAR_CALENDAR_ITEM',
            SET_ALL_LEVEL: 'SET_CALENDAR_LEVEL_ALL',
            SET_CALENDAR: 'SET_CALENDAR_CALENDAR',
            RESET: 'RESET_CALENDAR'
        }
    },
    REQUEST: {
        REQUEST_BYPASS: {
            SET_REQUEST_BYPASS: 'SET_REQUEST_BYPASS_REQUEST_BYPASS',
            SET_STUDENT: 'SET_REQUEST_BYPASS_STUDENT'
        },
        REQUEST_LIST: {
            SET_CALENDAR_ITEM_WITH_STATUS:
                'SET_REQUEST_LIST_CALEDNAR_ITEM_WTIH_STATUS',
            SET_ALL_SEMESTER: 'SET_REQUEST_LIST_ALL_SEMESTER',
            RESET: 'RESET_REQUEST_LIST'
        },
        REQUEST_ADD: {
            SET_CALENDAR_ITEM: 'SET_REQUEST_ADD_CALEDNAR_ITEM',
            SET_ALL_TEACHER: 'SET_REQUEST_ADD_ALL_TEACHER',
            SET_ALL_POSITION: 'SET_REQUEST_ADD_ALL_POSITION',
            SET_POST: 'SET_REQUEST_ADD_POST',
            SET_ALL_ROOM: 'SET_REQUEST_ADD_ALL_ROOM',
            SET_STEP: 'SET_REQUEST_ADD_ACTION_STEP',
            SET_CURRENT: 'SET_REQUEST_ADD_CURRENT',
            SET_COMPONENT: 'SET_REQUEST_ADD_COMPONENT',
            SET_PROJECT: 'SET_REQUEST_ADD_PROJECT',
            RESET: 'RESET_REQUEST_ADD'
        },
        REQUEST_DATA: {
            SET_ALL_USER_REQUEST: 'SET_REQUEST_DATA_ALL_USER_REQUEST',
            SET_ALL_ACTION: 'SET_REQUEST_DATA_ALL_ACTION',
            SET_STEP: 'SET_REQUEST_DATA_STEP',
            SET_STATUS: 'SET_REQUEST_DATA_STATUS',
            SET_RESET: 'RESET_REQUEST_DATA',
            SET_UNMOUNT: 'SET_REQUEST_DATA_UNMOUNT',
            UPDATE_USER_REQUEST: 'UPDATE_REQUEST_DATA_USER_REQUEST'
        }
    },
    EVALUATION: {
        EVALUATION_LIST: {
            SET_EVALUATION: 'SET_EVALUATION_LIST_EVALUATION',
            RESET: 'RESET_EVALUATION'
        },
        EVALUATION_ADD: {},
        EVALUATION_SUBMIT: {
            SET_EVALUATION: 'SET_EVALUATION_EVALUATION_SUBMIT',
            SET_SUBMITTED: 'SET_SUBMITTED_EVALUATION_SUBMIT',
            UPDATE_EVALUATION: 'UPDATE_EVALUATION_EVALUATIO_SUBMITN'
        },
        EVALUATION_ALL: {
            SET_USER_EVALUATION: 'SET_USER_EVALUATION_EVALUATION_ALL'
        }
    },
    DEFENSE: {
        DEFENSE_ALL: {
            SET_DEFENSE: 'SET_DEFENSE_ALL_DEFENSE',
            SET_ACTION: 'SET_DEFENSE_ALL_ACTION',
            RESET: 'RESET_DEFENSE_ALL'
        }
    },
    TODO: {
        TODO_ALL: {
            SET_TODO: 'SET_TODO_ALL_TODO'
        }
    },
    ADVISOR: {
        ADVISOR_LOAD: {
            SET_ADVISOR: 'SET_ADVISOR_LOAD_TEACHER'
        }
    }
}
