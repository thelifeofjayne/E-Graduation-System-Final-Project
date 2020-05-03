import React from 'react'
import {connect} from 'react-redux'
import {getAllLevel, getAllSemester} from '../../actions/calendar/calendar'
import {URL} from './../../config'
import {setHeader} from '../../actions/main'
import RequestOpen from './request-open'
import {
    getDefenseStatus, resetRequestData, getAllAction, getAllUserRequest,
    updateUserRequest, UpdateUserRequestList, deleteUserRequest
} from '../../actions/request/requestData'
import Tag from 'antd/lib/tag'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from "../loading";
import {getAllCalendar} from "../../actions/calendar/calendarList"
import Input from 'antd/lib/input'
import Checkbox from 'antd/lib/checkbox'
import Popconfirm from 'antd/lib/popconfirm'

@connect((store) => {
    return {
        userRequests: store.requestData.userRequests,
        actions: store.requestData.actions,
        semesters: store.calendar.semesters,
        levels: store.calendar.levels,
        lang: store.language.data,
        status: store.requestData.status,
        calendars: store.calendarList.all,
        config: store.main.config,
        currentUser: store.main.currentUser
    }
})
export default class RequestAll extends React.Component {

    constructor() {
        super()
        this.calendar = null
        this.level = null
        this.semester = null
        this.action = null
        this.state = {
            search: '',
            mine: true
        }
    }

    componentWillUnmount() {
        const {dispatch} = this.props
        dispatch(resetRequestData())
    }

    componentDidMount() {
        const {dispatch, userRequests, lang} = this.props
        dispatch(setHeader(lang.request_all.head))
        dispatch(getAllCalendar())
        dispatch(getAllUserRequest(this.calendar, this.level, this.semester, this.action))
        dispatch(getAllAction())
        dispatch(getAllSemester())
        dispatch(getAllLevel())
        dispatch(getDefenseStatus())
    }

    calendarChange(value) {
        this.calendar = value
        this.updateUserRequest()
    }

    levelChange(value) {
        this.level = value
        this.updateUserRequest()
    }

    semesterChange(value) {
        this.semester = value
        this.updateUserRequest()
    }

    actionChange(value) {
        this.action = value
        this.updateUserRequest()
    }

    updateUserRequest() {
        const {dispatch} = this.props
        dispatch(UpdateUserRequestList(null))
        dispatch(getAllUserRequest(this.calendar, this.level, this.semester, this.action))
    }

    delete(index) {
        const {userRequests, dispatch} = this.props
        const _user_request = userRequests.filter(user_request => user_request !== userRequests[index])
        dispatch(deleteUserRequest(userRequests[index], () => {
            userRequests.splice(index, 1)
            dispatch(UpdateUserRequestList(_user_request))
        }))
    }

    searchChange(search) {
        this.setState({search})
    }

    search() {
        const {userRequests} = this.props
        const {search} = this.state
        if (userRequests === null) return userRequests
        let user_requests = []
        userRequests.map(
            user_request => {
                if (user_request.student.user_id.includes(search) ||
                    user_request.student.student_fname.includes(search) ||
                    user_request.student.student_lname.includes(search))
                    user_requests.push(user_request)
            }
        )
        return user_requests
    }

    checkMine(mine) {
        this.setState({mine})
    }

    showMine() {
        const {userRequests, currentUser, config} = this.props
        console.log(this.props)
        if (userRequests === null) return userRequests
        let user_requests = []
        if (currentUser.user_type_id === config.PERSON_STUDENT_TYPE) {
            userRequests.map(
                user_request => {
                    if (user_request.student.id === currentUser.id)
                        user_requests.push(user_request)
                }
            )
        } else if (currentUser.user_type_id === config.PERSON_TEACHER_TYPE) {
            userRequests.map(
                user_request => {
                    user_request.advisors.map(
                        advisor => {
                            if (advisor.teacher.id === currentUser.id)
                                user_requests.push(user_request)
                        }
                    )
                    if (user_request.defenses.length !== 0) {
                        user_request.defenses[0].committees.map(
                            committee => {
                                if (committee.teacher.id === currentUser.id)
                                    user_requests.push(user_request)
                            }
                        )
                    }
                }
            )
        } else if (currentUser.user_type_id === config.PERSON_STAFF_TYPE) {
            return userRequests
        }
        return user_requests
    }

    render() {
        const {actions, semesters, levels, calendars, lang, status, config, currentUser} = this.props
        const {mine} = this.state
        const staff = currentUser.user_type_id === config.PERSON_STAFF_TYPE
        const userRequests = mine ? this.showMine() : this.search()
        return (
            calendars === null || levels === null || semesters === null || actions === null || status === null ?
                <Loading/> :
                <Row>
                    <Col sm={12} span={24} style={{textAlign: 'center', marginBottom: 8}}>
                        <select style={{minWidth: '80%', height: 32, padding: 0}}
                                onChange={(ev) => this.calendarChange(ev.target.value)}
                                defaultValue={calendars[calendars.findIndex(calendar => calendar.calendar_active === 1)].calendar_id}>
                            {
                                calendars.map(
                                    (calendar, index) =>
                                        <option key={index} value={calendar.calendar_id}>
                                            {calendar.calendar_id}
                                        </option>
                                )
                            }
                        </select>
                    </Col>
                    <Col sm={12} span={24} style={{textAlign: 'center', marginBottom: 8}}>
                        <select style={{minWidth: '80%', height: 32, padding: 0}}
                                defaultValue={levels[0].level_id}
                                onChange={(ev) => this.levelChange(ev.target.value)}>
                            {
                                levels.map(
                                    (level, index) =>
                                        <option key={index} value={level.level_id}>
                                            {level.level_name}
                                        </option>
                                )
                            }
                        </select>
                    </Col>
                    <Col sm={12} span={24} style={{textAlign: 'center', marginBottom: 8}}>
                        <select style={{minWidth: '80%', height: 32, padding: 0}}
                                defaultValue={semesters[0].semester_id}
                                onChange={(ev) => this.semesterChange(ev.target.value)}>
                            {
                                semesters.map(
                                    (semester, index) =>
                                        <option key={index} value={semester.semester_id}>
                                            {semester.semester_name}
                                        </option>
                                )
                            }
                        </select>
                    </Col>
                    <Col sm={12} span={24} style={{textAlign: 'center', marginBottom: 32}}>
                        <select style={{minWidth: '80%', height: 32, padding: 0}}
                                defaultValue={actions[0].action_id}
                                onChange={(ev) => this.actionChange(ev.target.value)}>
                            {
                                actions.map(
                                    (action, index) =>
                                        <option key={index} value={action.action_id}>
                                            {action.action_name}
                                        </option>
                                )
                            }
                        </select>
                    </Col>
                    <Col sm={16} span={24}/>
                    {
                        staff ? <Col sm={8} span={24}/> :
                            <Col sm={8} span={24} style={{marginBottom: 8}}>
                                <Checkbox onChange={(ev) => this.checkMine(ev.target.checked)} checked={mine}>
                                    {lang.request_all.mine}
                                </Checkbox>
                            </Col>
                    }
                    <Col sm={16} span={24}/>
                    <Col sm={8} span={24} style={{marginBottom: 8}}>
                        <Input placeholder={lang.request_all.search}
                               onChange={(ev) => this.searchChange(ev.target.value)}/>
                    </Col>
                    <Col span={24}>
                        {
                            <table class='table'>
                                <thead>
                                <tr>
                                    <th>
                                        <Row class='table-row' type='flex'>
                                            <Col class='text-center table-col' sm={6} span={24}>
                                                {lang.request_all.student}
                                            </Col>
                                            <Col class='text-center table-col' sm={5} span={24}>
                                                {lang.request_all.action}
                                            </Col>
                                            <Col class='text-center table-col' sm={5} span={24}>
                                                {lang.request_all.status}
                                            </Col>
                                            <Col class='text-center table-col' sm={staff ? 4 : 8} span={24}/>
                                            {
                                                !staff ? null :
                                                    <Col class='text-center table-col' sm={4} span={24}/>
                                            }
                                        </Row>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {
                                    userRequests === null ?
                                        <tr>
                                            <td>
                                                <Row class='table-row' type='flex'>
                                                    <Col class='text-center table-col' span={24}>
                                                        <Loading small/>
                                                    </Col>
                                                </Row>
                                            </td>
                                        </tr> :
                                        userRequests.length === 0 ?
                                            <tr>
                                                <td>
                                                    <Row class='table-row' type='flex'>
                                                        <Col class='text-center table-col' span={24}>
                                                            {lang.nodata}
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr> :
                                            userRequests.map(
                                                (userRequest, index) => {
                                                    const action = actions[actions.findIndex(action => action.action_id === userRequest.calendar_item.action_id)]
                                                    const step = userRequest.step
                                                    const current_step = step[step.findIndex(step => step.step.step_id === userRequest.current_step)]
                                                    return (
                                                        <tr key={index}>
                                                            <td>
                                                                <Row class='table-row' type='flex'>
                                                                    <Col class='text-center table-col' sm={6} span={24}>
                                                                        <div>{userRequest.student.user_id}</div>
                                                                        <div>{`${userRequest.student.prefix} ${userRequest.student.student_fname} ${userRequest.student.student_lname}`}</div>
                                                                    </Col>
                                                                    <Col class='text-center table-col' sm={5} span={24}>
                                                                        {action.action_name}
                                                                    </Col>
                                                                    <Col class='text-center table-col' sm={5} span={24}>
                                                                        {`${current_step.step.step_name} [${current_step.action_step_index + 1}/${userRequest.step.length}]`}
                                                                    </Col>
                                                                    <Col class='text-center table-col'
                                                                         sm={staff ? 4 : 8} span={24}>
                                                                        <RequestOpen index={index}
                                                                                     userRequest={userRequest}/>
                                                                    </Col>
                                                                    {
                                                                        !staff ? null :
                                                                            <Col class='text-center table-col' sm={4}
                                                                                 span={24}>
                                                                                <Popconfirm title="ยืนยัน?"
                                                                                            onConfirm={() => this.delete(index)}
                                                                                            okText="ยืนยัน"
                                                                                            cancelText="ยกเลิก">
                                                                                    <Tag class=' clickable tag-error'>
                                                                                        {lang.request_all.delete}
                                                                                    </Tag>
                                                                                </Popconfirm>
                                                                            </Col>
                                                                    }
                                                                </Row>
                                                            </td>
                                                        </tr>
                                                    )
                                                }
                                            )
                                }
                                </tbody>
                            </table>
                        }
                    </Col>
                </Row>
        )
    }
}