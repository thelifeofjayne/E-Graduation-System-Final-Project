import React from 'react'
import {connect} from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'
import Icon from 'antd/lib/icon'
import Loading from "../loading";
import {getAllAction} from "../../actions/request/requestData";
import {getAllSemester} from "../../actions/calendar/calendar";
import {
    deleteRequestBypass, getRequestBypass, getStudent,
    insertRequestBypass
} from "../../actions/request/request-bypass";
import Select from 'antd/lib/select'
import {setHeader} from "../../actions/main";

const Option = Select.Option

@connect((store) => {
    return {
        lang: store.language.data,
        action_bypasses: store.requestBypass.action_bypasses,
        students: store.requestBypass.students,
        actions: store.requestData.actions,
        semesters: store.calendar.semesters,
        config: store.main.config
    }
})
export default class RequestBypass extends React.Component {

    constructor() {
        super()
        this.state = {
            semester: null,
            action: null,
            student: null,
            adding: false,
            deleting: false
        }
    }

    componentDidMount() {
        const {dispatch, lang} = this.props
        dispatch(setHeader(lang.request_bypass.head))
        dispatch(getStudent())
        dispatch(getRequestBypass())
        dispatch(getAllAction(response => {
            this.setState({action: response[0].action_id})
        }))
        dispatch(getAllSemester(response => {
            this.setState({semester: response[0].semester_id})
        }))
    }

    semesterChange(semester) {
        semester = parseInt(semester)
        this.setState({semester})
        return semester
    }

    actionChange(action) {
        action = parseInt(action)
        this.setState({action})
        return action
    }

    studentChange(student) {
        this.setState({student})
    }

    add() {
        const {dispatch} = this.props
        const {semester, action, student, adding} = this.state
        if (student !== null) {
            if (!adding) {
                this.setState({adding: true})
                dispatch(insertRequestBypass(semester, action, student, () => {
                    this.setState({adding: false})
                }))
            }
        } else {
            alert('DAFRAN IS THE BEST TRACER IN THE WORLD')
        }
    }

    delete(semester, action, student) {
        const {dispatch} = this.props
        const {deleting} = this.state
        if (!deleting) {
            this.setState({deleting: true})
            dispatch(deleteRequestBypass(semester, action, student, () => {
                this.setState({deleting: false})
            }))
        }
    }

    render() {
        const {students, action_bypasses, actions, semesters, lang, config} = this.props
        const {adding, deleting} = this.state
        return (
            students === null || action_bypasses === null || this.state.action === null || this.state.semester === null ?
                <Loading/> :
                <Row>
                    <Col sm={7} span={24} class='text-center' style={{marginBottom: 8}}>
                        <Select style={{width: '80%', height: 32, padding: 0}} showSearch optionFilterProp="children"
                                filterOption={(input, option) => {
                                    if (option.props.children === null) return false
                                    return option.props.children.toLowerCase().indexOf(input.toLowerCase()) >= 0
                                }}
                                onChange={(value) => this.studentChange(value)}>
                            {
                                students.map(
                                    (student, index) =>
                                        <Option key={index} value={student.id}>
                                            {student.student_fname}
                                        </Option>
                                )
                            }
                        </Select>
                    </Col>
                    <Col sm={7} span={24} class='text-center' style={{marginBottom: 8}}>
                        <select style={{width: '80%', height: 32, padding: 0}}
                                defaultValue={this.state.semester}
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
                    <Col sm={7} span={24} class='text-center' style={{marginBottom: 8}}>
                        <select style={{width: '80%', height: 32, padding: 0}}
                                defaultValue={this.state.action}
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
                    <Col sm={3} span={24} class='text-center' style={{marginBottom: 32}}>
                        <Tag class='tag-medium tag-success clickable' onClick={() => this.add()}>
                            {
                                adding ? <Icon type="loading"/> :
                                    <div>
                                        <Icon type="plus"/> {lang.request_bypass.add}
                                    </div>
                            }
                        </Tag>
                    </Col>
                    <Col span={24}>
                        <table class='table'>
                            <thead>
                            <tr>
                                <th>
                                    <Row class='table-row' type='flex'>
                                        <Col class='text-center table-col' sm={6} span={24}>
                                            {lang.request_bypass.name}
                                        </Col>
                                        <Col class='text-center table-col' sm={6} span={24}>
                                            {lang.request_bypass.action}
                                        </Col>
                                        <Col class='text-center table-col' sm={6} span={24}>
                                            {lang.request_bypass.semester}
                                        </Col>
                                        <Col class='text-center table-col' sm={6} span={24}/>
                                    </Row>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                action_bypasses.length === 0 ?
                                    <tr>
                                        <td>
                                            <Row class='table-row' type='flex'>
                                                <Col class='text-center table-col' span={24}>
                                                    {lang.nodata}
                                                </Col>
                                            </Row>
                                        </td>
                                    </tr> :
                                    action_bypasses.map(
                                        (action_bypass, index) => {
                                            const action = actions[actions.findIndex(action => action.action_id === action_bypass.action_id)]
                                            const semester = semesters[semesters.findIndex(semester => semester.semester_id === action_bypass.semester_id)]
                                            return (
                                                <tr key={index}>
                                                    <td>
                                                        <Row class='table-row' type='flex'>
                                                            <Col class='text-center table-col' sm={6} span={24}>
                                                                {action_bypass.student.student_fname}
                                                            </Col>
                                                            <Col class='text-center table-col' sm={6} span={24}>
                                                                {action.action_name}
                                                            </Col>
                                                            <Col class='text-center table-col' sm={6} span={24}>
                                                                {semester.semester_name}
                                                            </Col>
                                                            <Col class='text-center table-col' sm={6} span={24}>
                                                                <Tag class='tag-error clickable'
                                                                     onClick={() => this.delete(semester.semester_id, action.action_id, action_bypass.student.id)}>
                                                                    {deleting ? <Icon
                                                                        type='loading'/> : lang.request_bypass.delete}
                                                                </Tag>
                                                            </Col>
                                                        </Row>
                                                    </td>
                                                </tr>
                                            )
                                        }
                                    )
                            }
                            </tbody>
                        </table>
                    </Col>
                </Row>
        )
    }
}