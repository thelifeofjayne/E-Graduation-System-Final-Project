import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../loading'
import {
    getEvaluation,
    getSubmittied,
    insertUserEvaluation,
    updateEvaluation
} from '../../actions/evaluation/evaluation-submit'
import Radio from 'antd/lib/radio'
import Tag from 'antd/lib/tag'
import InputNumber from 'antd/lib/input-number'

import Input from 'antd/lib/input'

const { TextArea } = Input

const RadioGroup = Radio.Group

@connect(store => {
    return {
        lang: store.language.data,
        evaluation: store.evaluationSubmit.evaluation,
        submitted: store.evaluationSubmit.submitted,
        calendarItem: store.requestAdd.calendarItem
    }
})
export default class EvaluationSubmit extends React.Component {
    constructor() {
        super()
        this.data = {}
        this.checkmark = '\u2713'
        this.nothing = '\u0020\u0020\u0020'
        this.personal = [
            {
                head: 'เพศ',
                name: 'gender',
                list: ['ชาย', 'หญิง']
            },
            {
                head: 'อายุ',
                name: 'age',
                list: ['๒๐-๓๐ ปี', '๓๑-๔๐ ปี', '๔๑-๕๐ ปี', 'มากกว่า ๕๐ ปี']
            },
            {
                head: 'ระดับการศึกษา',
                name: 'degree',
                list: ['ปริญญาโท', 'ปริญญาเอก']
            },
            {
                head: 'สาขาวิชา',
                name: 'branch',
                list: [
                    'วิทยาการคอมพิวเตอร์',
                    'เทคโนโลยีสารสนเทศ',
                    'การรับรู้ระยะไกลและระบบสารสนเทศภูมิศาสตร์'
                ]
            },
            {
                head: 'กำลังศึกษาชั้นปีที่',
                name: 'year',
                list: ['ปี 1', 'ปี 2', 'ปี 3', 'ปี 4']
            },
            {
                head: 'ภาคการศึกษา',
                name: 'semester',
                list: ['ต้น', 'ปลาย']
            },
            {
                head: 'ปีการศึกษา',
                name: 'enroll',
                list: [0],
                year: 1
            }
        ]
        this.personal.map((personal, index1) => {
            personal.list.map((list, index2) => {
                this.data[`${personal.name}${index2}`] = null
            })
        })
        this.rate = ['น้อยที่สุด', 'น้อย', 'ปานกลาง', 'มาก', 'มากที่สุด']
        this.comment = null
    }

    componentWillUnmount() {
        const { dispatch } = this.props
        /* TODO : RESET */
    }

    componentDidMount() {
        const { dispatch } = this.props
        dispatch(getEvaluation())
        dispatch(getSubmittied())
    }

    changePersonal(ev) {
        Object.keys(this.data).some(k => {
            if (~k.indexOf(ev.target.name)) {
                if (k === `${ev.target.name}${ev.target.value}`)
                    this.data[k] = this.checkmark
                else this.data[k] = this.nothing
            }
        })
    }

    change(value, index1, index2) {
        const { evaluation, dispatch } = this.props
        const evaluation_topic =
            evaluation.evaluation_topic_group[index1].evaluation_topic[index2]
        const topic = Object.assign({}, evaluation_topic, {
            ...evaluation_topic,
            value
        })
        const evaluation_ = Object.assign({}, evaluation, {
            ...evaluation,
            evaluation_topic_group: [
                ...evaluation.evaluation_topic_group.slice(0, index1),
                {
                    ...evaluation.evaluation_topic_group[index1],
                    evaluation_topic: [
                        ...evaluation.evaluation_topic_group[
                            index1
                        ].evaluation_topic.slice(0, index2),
                        topic,
                        ...evaluation.evaluation_topic_group[
                            index1
                        ].evaluation_topic.slice(index2 + 1)
                    ]
                },
                ...evaluation.evaluation_topic_group.slice(index1 + 1)
            ]
        })
        dispatch(updateEvaluation(evaluation_))
    }

    submit() {
        const { evaluation, calendarItem, dispatch } = this.props
        let pass = true
        let data = {}
        data.comment = this.comment === null ? this.nothing : this.comment
        Object.keys(this.data).map(key => {
            if (this.data[key] === null || this.data[key] === '') pass = false
        })
        evaluation.evaluation_topic_group.map((group, index1) => {
            group.evaluation_topic.map((topic, index2) => {
                if (topic.value === undefined) pass = false
                this.rate.map((rate, index3) => {
                    data['rate' + index1 + '' + index2 + '' + index3] =
                        topic.value === index3 ? this.checkmark : this.nothing
                })
            })
        })
        if (!pass) {
            alert(5555)
            return
        }
        data = { ...this.data, ...data }
        evaluation.data = JSON.stringify({ data })
        dispatch(insertUserEvaluation(calendarItem, evaluation))
    }

    changeText(name, value) {
        this.data[`${name}0`] = value
    }

    commentChange(comment) {
        this.comment = comment
    }

    render() {
        const { evaluation, submitted, lang } = this.props
        return evaluation === null || submitted === null ? (
            <Loading />
        ) : submitted ? (
            'XD'
        ) : (
            <Row>
                <Col span={24} style={{ textAlign: 'center' }}>
                    <h3>ความพึงพอใจต่อคุณภาพหลักสูตร</h3>
                </Col>
                <Col span={24} style={{ textAlign: 'center' }}>
                    <h3>ข้อมูลเบื้องต้นของนักศึกษา</h3>
                </Col>
                {this.personal.map((personal, index1) => [
                    <Col sm={6} span={24} key={index1}>
                        <Col span={24}>{personal.head}</Col>
                        {personal.year ? (
                            <InputNumber
                                size='small'
                                placeholder='year'
                                onChange={value =>
                                    this.changeText(personal.name, value)
                                }
                                maxLength={4}
                            />
                        ) : (
                            <RadioGroup
                                name={personal.name}
                                onChange={ev => this.changePersonal(ev)}
                            >
                                {personal.list.map((list, index2) => (
                                    <Col key={index2} span={24}>
                                        <Radio value={index2}>{list}</Radio>
                                    </Col>
                                ))}
                            </RadioGroup>
                        )}
                    </Col>,
                    (index1 + 1) % 4 !== 0 ||
                    index1 === this.personal.length - 1 ? null : (
                        <Col key={index1 + '_'} span={24} />
                    )
                ])}
                <Col span={24}>
                    {evaluation.evaluation_topic_group.map((group, index1) => (
                        <Col
                            key={index1}
                            span={24}
                            style={{ textAlign: 'center' }}
                        >
                            <h4>{`${index1 + 1}. ${
                                group.evaluation_topic_group_name
                            }`}</h4>
                            {group.evaluation_topic.map((topic, index2) => (
                                <Row key={index2}>
                                    <Col span={24}>
                                        <h5>
                                            {`(${index2 + 1}) ${
                                                topic.evaluation_topic_name
                                            }`}
                                        </h5>
                                    </Col>
                                    <Col span={24}>
                                        <RadioGroup
                                            name={index1 + '' + index2}
                                            onChange={ev =>
                                                this.change(
                                                    ev.target.value,
                                                    index1,
                                                    index2
                                                )
                                            }
                                        >
                                            {this.rate.map((rate, index3) => (
                                                <Radio
                                                    key={index3}
                                                    value={index3}
                                                    checked={
                                                        evaluation
                                                            .evaluation_topic_group[
                                                            index1
                                                        ].evaluation_topic[
                                                            index2
                                                        ].value === index3
                                                    }
                                                >
                                                    {rate}
                                                </Radio>
                                            ))}
                                        </RadioGroup>
                                    </Col>
                                    <Col span={24}>
                                        <hr />
                                    </Col>
                                </Row>
                            ))}
                        </Col>
                    ))}
                </Col>
                <Col span={24} style={{ marginBottom: 16 }}>
                    <h5>{lang.evaluation_submit.comment}</h5>
                    <Input
                        placeholder='comment'
                        onChange={ev => this.commentChange(ev.target.value)}
                    />
                </Col>
                <Col span={24}>
                    <Tag
                        class='clickable tag-success tag-big margin-0'
                        onClick={() => this.submit()}
                    >
                        {lang.evaluation_submit.submit}
                    </Tag>
                </Col>
            </Row>
        )
    }
}
