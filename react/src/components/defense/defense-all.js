import React from 'react'
import {connect} from 'react-redux'
import {getAllLevel, getAllSemester} from '../../actions/calendar/calendar'
import {URL} from './../../config'
import {setHeader} from '../../actions/main'
import Tag from 'antd/lib/tag'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from "../loading";
import {getAllCalendar} from "../../actions/calendar/calendarList";
import {getAction, getDefense, resetDefenseAll, UpdateDefense} from "../../actions/defense/defense-all";
import JSZip from 'jszip'
import Docxtemplater from 'docxtemplater'
import JSZipUtils from 'jszip-utils'
import FileSaver from 'file-saver'
import Icon from 'antd/lib/icon'
import Checkbox from 'antd/lib/checkbox'

@connect((store) => {
    return {
        config: store.main.config,
        defenses: store.defenseAll.defenses,
        actions: store.defenseAll.actions,
        semesters: store.calendar.semesters,
        levels: store.calendar.levels,
        lang: store.language.data,
        calendars: store.calendarList.all
    }
})
export default class DefenseAll extends React.Component {

    constructor() {
        super()
        this.calendar = null
        this.level = null
        this.semester = null
        this.action = null
        this.checkBox = []
        this.state = {
            indeterminate: false,
            checked: false
        }
    }

    componentWillUnmount() {
        const {dispatch} = this.props
        dispatch(resetDefenseAll())
    }

    componentDidMount() {
        const {dispatch, lang} = this.props
        dispatch(setHeader(lang.defense_all.head))
        dispatch(getAllCalendar())
        dispatch(getAllSemester())
        dispatch(getAllLevel())
        dispatch(getDefense(this.calendar, this.level, this.semester, this.action))
        dispatch(getAction())
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
        dispatch(UpdateDefense(null))
        dispatch(getDefense(this.calendar, this.level, this.semester, this.action))
    }

    moneify(money) {
        return money.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    }

    print_defense_fee() {
        const {defenses, config} = this.props
        let data = []
        this.checkBox.map(
            (checkBox, index) => {
                if (checkBox.rcCheckbox.state.checked) {
                    const defense = defenses[index]
                    let committee_fee = 0
                    let advisor_fee = 0
                    let committee_main_each_fee = 0
                    let committee_co_each_fee = 0
                    let advisors = []
                    let committees = []
                    defense.defense_advisor.map(
                        (advisor, index) => {
                            let advisor_ = {
                                index: index + 1,
                                name: `${advisor.teacher.prefix} ${advisor.teacher.person_fname} ${advisor.teacher.person_lname}`,
                                fee: this.moneify(advisor.advisor_fee_amount)
                            }
                            advisor_fee += advisor.advisor_fee_amount
                            advisors.push(advisor_)
                        }
                    )
                    defense.committees.map(
                        (committee, index) => {
                            let committee_ = {
                                index: index + 1,
                                name: `${committee.teacher.prefix} ${committee.teacher.person_fname} ${committee.teacher.person_lname}`,
                                position: committee.position.position_name,
                                fee: this.moneify(committee.fee)
                            }
                            if (committee.position.position_id === config.COMMITTEE_MAIN_POSITION) {
                                committee_main_each_fee = committee.fee
                            }
                            if (committee.position.position_id === config.COMMITTEE_CO_POSITION) {
                                committee_co_each_fee = committee.fee
                            }
                            committee_fee += committee.fee
                            committees.push(committee_)
                        }
                    )
                    let data_ = {
                        plan: defense.student.plan.plan_name,
                        plan_type: defense.student.plan_type.plan_type_name,
                        level: defense.student.level.level_name,
                        program: defense.student.program.program_name,
                        branch: defense.student.branch.branch_name,
                        student_id: defense.student.person.user_id,
                        student_name: `${defense.student.person.prefix} ${defense.student.person.student_fname} ${defense.student.person.student_lname}`,
                        committees, advisors,
                        advisor_fee: this.moneify(advisor_fee),
                        committee_fee: this.moneify(committee_fee),
                        committee_main_each_fee: this.moneify(committee_main_each_fee),
                        committee_co_each_fee: this.moneify(committee_co_each_fee),
                        total_fee: this.moneify(advisor_fee + committee_fee)
                    }
                    data.push(data_)
                }
            }
        )
        this.print('fee', {data})
    }

    print_defense() {
        const {defenses} = this.props
        let data = []
        this.checkBox.map(
            (checkBox, index) => {
                if (checkBox.rcCheckbox.state.checked) {
                    const defense = defenses[index]
                    let committees = []
                    defense.committees.map(
                        committee => {
                            let committee_ = {
                                name: `${committee.teacher.prefix} ${committee.teacher.person_fname} ${committee.teacher.person_lname}`,
                                position: committee.position.position_name
                            }
                            committees.push(committee_)
                        }
                    )
                    let data_ = {
                        index: index + 1,
                        date: defense.defense_date,
                        time: `${defense.defense_time_start} - ${defense.defense_time_end}`,
                        program: defense.student.program.program_name,
                        student_id: defense.student.person.user_id,
                        student_name: `${defense.student.person.prefix} ${defense.student.person.student_fname} ${defense.student.person.student_lname}`,
                        project_name_th: defense.project.project_name_th,
                        project_name_en: defense.project.project_name_en,
                        student_plan_type: defense.student.plan_type.plan_type_name,
                        committee: committees,
                        room: defense.room.room_name
                    }
                    data.push(data_)
                }
            }
        )
        this.print('defense', {data})
    }

    print(doc_name, data) {
        JSZipUtils.getBinaryContent(`${URL.BASE}/web_egs/docx/${doc_name}.docx`, (error, content) => {
            if (error)
                throw error
            let zip = new JSZip(content);
            let doc = new Docxtemplater().loadZip(zip)
            doc.setData(data)
            doc.render()
            let out = doc.getZip().generate({
                type: "blob",
                mimeType: "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            })
            FileSaver.saveAs(out, "output.docx")
        })
    }

    check(checked, index_) {
        let isTrue = []
        let isFalse = []
        this.checkBox.map((checkBox, index) => {
            if (index === index_)
                checked ? isTrue.push(true) : isFalse.push(false)
            else
                checkBox.rcCheckbox.state.checked ? isTrue.push(true) : isFalse.push(false)
        })
        if (isTrue.length === 0)
            this.setState({checked: false, indeterminate: false})
        else if (isFalse.length === 0)
            this.setState({checked: true, indeterminate: false})
        else
            this.setState({checked: false, indeterminate: true})
        console.log(this.checkBox)
    }

    mainCheck() {
        const {indeterminate, checked} = this.state
        if (indeterminate)
            this.setState({checked: true, indeterminate: false})
        if (checked)
            this.setState({checked: false, indeterminate: false})
        else
            this.setState({checked: true, indeterminate: false})
        this.checkBox.map((checkBox, index) => {
            checkBox.rcCheckbox.setState({checked: !checked})
        })
    }

    render() {
        const {defenses, actions, semesters, levels, calendars, lang} = this.props
        const {indeterminate, checked} = this.state
        this.checkBox = []
        return (
            calendars === null || levels === null || semesters === null || actions === null ? <Loading/> :
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
                    <Col sm={12} span={24} style={{textAlign: 'center', marginBottom: 32}}>
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
                    <Col span={24} style={{textAlign: 'right', marginBottom: 8}}>
                        <Tag class='clickable tag-medium tag-default' onClick={() => this.print_defense()}>
                            <Icon style={{marginRight: 8}} type="printer"/>
                            {lang.defense_all.print_defense}
                        </Tag>
                        <Tag class='clickable tag-medium tag-default' onClick={() => this.print_defense_fee()}>
                            <Icon style={{marginRight: 8}} type="printer"/>
                            {lang.defense_all.print_fee}
                        </Tag>
                    </Col>
                    <Col span={24}>
                        {
                            <table class='table'>
                                <thead>
                                <tr>
                                    <th>
                                        <Row class='table-row' type='flex'>
                                            <Col class='text-center table-col' sm={2} span={24}>
                                                <Checkbox indeterminate={indeterminate}
                                                          onChange={() => this.mainCheck()}
                                                          checked={checked}/>
                                            </Col>
                                            <Col class='text-center table-col' sm={5} span={24}>
                                                {lang.defense_all.student_id}
                                            </Col>
                                            <Col class='text-center table-col' sm={5} span={24}>
                                                {lang.defense_all.student_name}
                                            </Col>
                                            <Col class='text-center table-col' sm={12} span={24}>
                                                {lang.defense_all.defense_type}
                                            </Col>
                                        </Row>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {
                                    defenses === null ?
                                        <tr>
                                            <td>
                                                <Row class='table-row' type='flex'>
                                                    <Col class='text-center table-col' span={24}>
                                                        <Loading small/>
                                                    </Col>
                                                </Row>
                                            </td>
                                        </tr> :
                                        defenses.length === 0 ?
                                            <tr>
                                                <td>
                                                    <Row class='table-row' type='flex'>
                                                        <Col class='text-center table-col' span={24}>
                                                            {lang.nodata}
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr> :
                                            defenses.map(
                                                (defense, index) => {
                                                    const action = actions[actions.findIndex(action => action.action_id === defense.defense_type.action_id)]
                                                    return (
                                                        <tr key={index}>
                                                            <td>
                                                                <Row class='table-row' type='flex'>
                                                                    <Col class='text-center table-col' sm={2} span={24}>
                                                                        <Checkbox ref={ref => {
                                                                            if (ref !== null) this.checkBox.push(ref)
                                                                        }}
                                                                                  onChange={(ev) => this.check(ev.target.checked, index)}/>
                                                                    </Col>
                                                                    <Col class='text-center table-col' sm={5} span={24}>
                                                                        {defense.student.person.user_id}
                                                                    </Col>
                                                                    <Col class='text-center table-col' sm={5} span={24}>
                                                                        {`${defense.student.person.prefix} ${defense.student.person.student_fname} ${defense.student.person.student_lname}`}
                                                                    </Col>
                                                                    <Col class='text-center table-col' sm={12}
                                                                         span={24}>
                                                                        {action.action_name}
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
                        }
                    </Col>
                </Row>
        )
    }
}