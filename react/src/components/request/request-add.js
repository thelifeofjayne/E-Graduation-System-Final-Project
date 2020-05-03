import React from 'react'
import {connect} from 'react-redux'
import Steps from 'antd/lib/steps'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Icon from 'antd/lib/icon'
import {
    getStep, setComponent, getCalendarItem,
    getTeacher, getPosition, setPost,
    getRoom, resetRequestAdd, getProject
} from '../../actions/request/requestAdd'
import Teacher from './step/teacher'
import Defense from './step/defense'
import Detail from './step/detail'
import Summary from './step/Summary'
import Button from "./step/button";
import Loading from "../loading";
import EvaluationSubmit from './../evaluation/evaluation-submit'
import {setHeader} from "../../actions/main";

const Step = Steps.Step

@connect((store) => {
    return {
        lang: store.language.data,
        project: store.requestAdd.project,
        steps: store.requestAdd.steps,
        current: store.requestAdd.current,
        component: store.requestAdd.component,
        positions: store.requestAdd.positions,
        rooms: store.requestAdd.rooms
    }
})
export default class RequestAdd extends React.Component {
    constructor() {
        super()
        this.component = {Teacher, Defense, Detail, Summary, EvaluationSubmit}
    }

    componentWillUnmount() {
        const {dispatch} = this.props
        dispatch(resetRequestAdd())
    }

    componentDidMount() {
        const {dispatch, lang, calendarId, levelId, semesterId, actionId, ownerId, component, current} = this.props
        dispatch(setHeader(lang.request_add.head))
        dispatch(getStep(actionId, response => {
            dispatch(setComponent(response[current].step.step_component))
        }))
        dispatch(getCalendarItem(ownerId, calendarId, levelId, semesterId, actionId, calendarItem => {
                dispatch(getTeacher(calendarItem.request_defense.length === 0))
                this.edit = calendarItem.user_request !== null && !calendarItem.action.action_default
                if (calendarItem.user_request !== null)
                    this.initPost(calendarItem)
            }
        ))
        dispatch(getProject())
        dispatch(getPosition(actionId))
        dispatch(getRoom())
    }

    initPost(calendarItem) {
        const {dispatch, post} = this.props
        let defenses = []
        const userRequest = calendarItem.user_request
        userRequest.defenses === undefined ? null :
            calendarItem.request_defense.map(
                request_defense => {
                    const defense = userRequest.defenses[userRequest.defenses.findIndex(defense => defense.defense_type.action_id === request_defense.action.action_id)]
                    const defense_ = {
                        date: defense.defense_date,
                        end: defense.defense_time_end,
                        room: defense.room.room_id,
                        start: defense.defense_time_start,
                        type: defense.defense_type.action_id
                    }
                    defenses.push(defense_)

                }
            )
        const teachers_ = userRequest.advisors.length !== 0 ? userRequest.advisors :
            userRequest.defenses.length === 0 ? [] : userRequest.defenses[0].committees
        let teachers = []
        teachers_.map(teacher_ => {
            const teacher = {
                teacher: teacher_.teacher_id,
                position: teacher_.position_id
            }
            teachers.push(teacher)
        })
        const newPost = Object.assign({}, post, {
            ...post, defenses, teachers
        })
        dispatch(setPost(newPost))
    }

    render() {
        const {lang, steps, current, component, positions, rooms, project} = this.props
        const Component = this.component[component]
        return (
            rooms === null || positions === null || steps === null || component === null || project === undefined ?
                <Loading/> :
                <Row type='flex' justify='center'>
                    <Col span={20}>
                        <Row>
                            <Col span={24}>
                                <Steps size='small' style={{marginBottom: 32}} current={current}>
                                    {
                                        steps.map(
                                            (step, index) =>
                                                <Step key={index} title={step.step.step_name}/>
                                        )
                                    }
                                </Steps>
                            </Col>
                        </Row>
                        <Row type='flex' justify='center'>
                            <Col>
                                <Component/>
                            </Col>
                        </Row>
                    </Col>
                </Row>
        )
    }
}