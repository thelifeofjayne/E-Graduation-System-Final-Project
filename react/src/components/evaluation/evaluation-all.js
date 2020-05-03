import React from 'react'
import {connect} from 'react-redux'
import {URL} from '../../config'
import JSZip from 'jszip'
import Docxtemplater from 'docxtemplater'
import JSZipUtils from 'jszip-utils'
import FileSaver from 'file-saver'
import Loading from "../loading";
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Checkbox from 'antd/lib/checkbox'
import Tag from 'antd/lib/tag'
import Icon from 'antd/lib/icon'
import {getUserEvaluation, UpdateUserEvaluation} from "../../actions/evaluation/evaluation-all";
import {getAllSemester} from "../../actions/calendar/calendar";
import {getAllCalendar} from "../../actions/calendar/calendarList";
import {setHeader} from "../../actions/main";

@connect((store) => {
    return {
        userEvaluations: store.evaluationAll.userEvaluations,
        lang: store.language.data,
        semesters: store.calendar.semesters,
        calendars: store.calendarList.all
    }
})
export default class EvaluationAll extends React.Component {
    constructor() {
        super()
        this.checkBox = []
        this.calendar = null
        this.semester = null
        this.state = {
            indeterminate: false,
            checked: false
        }
    }

    componentDidMount() {
        const {dispatch, lang} = this.props
        dispatch(setHeader(lang.evaluation_all.head))
        dispatch(getAllCalendar())
        dispatch(getAllSemester())
        dispatch(getUserEvaluation(this.calendar, this.semester))
    }

    print() {
        const {userEvaluations} = this.props
        let data = {}
        data.components = []
        this.checkBox.map(
            (checkBox, index) => {
                if (checkBox.rcCheckbox.state.checked)
                    data.components.push(JSON.parse(userEvaluations[index].user_evaluation_data).data)
            }
        )

        JSZipUtils.getBinaryContent(`${URL.BASE}/web_egs/uploads/eval/eval_template_${userEvaluations[0].evaluation_id}.docx`, (error, content) => {
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

    calendarChange(value) {
        this.calendar = value
        this.updateUserEvaluation()
    }

    semesterChange(value) {
        this.semester = value
        this.updateUserEvaluation()
    }

    updateUserEvaluation() {
        const {dispatch} = this.props
        dispatch(UpdateUserEvaluation(null))
        dispatch(getUserEvaluation(this.calendar, this.semester))
    }

    render() {
        const {userEvaluations, lang, calendars, semesters} = this.props
        const {indeterminate, checked} = this.state
        this.checkBox = []
        return (
            calendars === null || semesters === null ? <Loading/> :
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
                    <Col span={24} style={{textAlign: 'right', marginBottom: 8}}>
                        <Tag class='clickable tag-medium tag-default' onClick={() => this.print()}>
                            <Icon style={{marginRight: 8}} type="printer"/> {lang.evaluation_all.print}
                        </Tag>
                    </Col>
                    <Col span={24}>
                        <table class='table'>
                            <thead>
                            <tr>
                                <th>
                                    <Row class='table-row' type='flex'>
                                        <Col class='text-center table-col' sm={2} span={24}>
                                            <Checkbox indeterminate={indeterminate} onChange={() => this.mainCheck()}
                                                      checked={checked}/>
                                        </Col>
                                        <Col class='text-center table-col' sm={11} span={24}>
                                            {lang.evaluation_all.student}
                                        </Col>
                                        <Col class='text-center table-col' sm={11} span={24}>
                                            {lang.evaluation_all.name}
                                        </Col>
                                    </Row>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                userEvaluations === null ?
                                    <tr>
                                        <td>
                                            <Row class='table-row' type='flex'>
                                                <Col class='text-center table-col' span={24}>
                                                    <Loading small/>
                                                </Col>
                                            </Row>
                                        </td>
                                    </tr> :
                                    userEvaluations.length === 0 ?
                                        <tr>
                                            <td>
                                                <Row class='table-row' type='flex'>
                                                    <Col class='text-center table-col' span={24}>
                                                        {lang.nodata}
                                                    </Col>
                                                </Row>
                                            </td>
                                        </tr> :
                                        userEvaluations.map(
                                            (userEvaluation, index) =>
                                                <tr key={index}>
                                                    <td>
                                                        <Row class='table-row' type='flex'>
                                                            <Col class='text-center table-col' sm={2} span={24}>
                                                                <Checkbox ref={ref => {
                                                                    if (ref !== null) this.checkBox.push(ref)
                                                                }}
                                                                          onChange={(ev) => this.check(ev.target.checked, index)}/>
                                                            </Col>
                                                            <Col class='text-center table-col' sm={11} span={24}>
                                                                {`${userEvaluation.student.prefix} ${userEvaluation.student.student_fname} ${userEvaluation.student.student_lname}`}
                                                            </Col>
                                                            <Col class='text-center table-col' sm={11} span={24}>
                                                                {userEvaluation.evaluation.evaluation_name}
                                                            </Col>
                                                        </Row>
                                                    </td>
                                                </tr>
                                        )
                            }
                            </tbody>
                        </table>
                    </Col>
                </Row>
        )
    }
}