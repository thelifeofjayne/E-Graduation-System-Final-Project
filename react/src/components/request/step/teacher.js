import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../../loading'
import Button from '../step/button'
import { setPost } from '../../../actions/request/requestAdd'
import Select from 'antd/lib/select'
import Tag from 'antd/lib/tag'
import { URL } from '../../../config'

const { Option, OptGroup } = Select

@connect(
    store => {
        return {
            lang: store.language.data,
            positions: store.requestAdd.positions,
            teachers: store.requestAdd.teachers,
            post: store.requestAdd.post,
            calendarItem: store.requestAdd.calendarItem,
            config: store.main.config
        }
    },
    null,
    null,
    { withRef: true }
)
export default class Teacher extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            teachers: null,
            value: null
        }
    }

    componentDidMount() {
        this.init()
    }

    init() {
        const { post, positions } = this.props
        let value = {}
        positions.map(position => {
            let teachers = post.teachers.filter(
                teacher => teacher.position === position.position_id
            )
            let values = []
            teachers.map(teacher => {
                values.push(teacher.teacher)
            })
            value[position.position_id] = values
        })
        this.setState({ value, teachers: post.teachers })
    }

    onChange(values, position) {
        if (values.length > position.position_maximum) {
            alert('max')
            return
        }
        const { teachers, value } = this.state
        let teachers_ = teachers.filter(
            teacher => teacher.position !== position.position_id
        )
        values.map(teacher => {
            teachers_.push({
                teacher,
                position: position.position_id
            })
        })
        let _value = Object.assign({}, value)
        _value[position.position_id] = values
        this.setState({ value: _value })
        this.setState({ teachers: teachers_ })
    }

    validate() {
        const { value } = this.state
        const { positions } = this.props
        let position = true
        positions.map(_position => {
            if (
                value[_position.position_id].length < _position.position_minimum
            ) {
                alert(`min`)
                position = false
                return
            }
        })
        if (!position) return false
        this.setPost()
        return true
    }

    setPost() {
        const { teachers } = this.state
        const { dispatch, post } = this.props
        let _post = Object.assign({}, post, {
            ...post,
            teachers
        })
        dispatch(setPost(_post))
    }

    ex_committee() {
        const { calendarItem, positions } = this.props
        let teachers = []
        calendarItem.ex_committee.map(committee => {
            const teacher = {
                teacher: committee.teacher_id,
                position: committee.position_id
            }
            teachers.push(teacher)
        })
        let value = {}
        positions.map(position => {
            let _teachers = calendarItem.ex_committee.filter(
                teacher => teacher.position_id === position.position_id
            )
            let values = []
            _teachers.map(teacher => {
                values.push(teacher.teacher_id)
            })
            value[position.position_id] = values
        })
        this.setState({ teachers, value })
    }

    render() {
        const {
            lang,
            post,
            positions,
            teachers,
            config,
            calendarItem
        } = this.props
        const { value } = this.state
        return teachers === null ||
            this.state.teachers === null ||
            value === null ? (
            <Loading />
        ) : (
            <div>
                {calendarItem.request_defense.length !== 0 ? null : (
                    <div class='text-center margin-bottom-16'>
                        <a
                            href={URL.ADVISOR.ADVISOR_LOAD.MAIN.LINK}
                            target='_blank'
                        >
                            <Tag class='clickable tag-default'>
                                {lang.teacher.teacher_load}
                            </Tag>
                        </a>
                    </div>
                )}
                {calendarItem.ex_committee.length === 0 ? null : (
                    <div class='text-center margin-bottom-16'>
                        <Tag
                            class='clickable tag-default'
                            onClick={() => this.ex_committee()}
                        >
                            {lang.teacher.ex_committee}
                        </Tag>
                    </div>
                )}
                {positions.map((position, index) => (
                    <Row key={index} type='flex' class='step text-center'>
                        <Col span={24}>{position.position_name}</Col>
                        <Col span={24} class='margin-bottom-16'>
                            <Select
                                mode='multiple'
                                style={{ minWidth: 250, maxWidth: 250 }}
                                placeholder='SELECT TEACHER PLS'
                                value={value[position.position_id]}
                                onChange={value =>
                                    this.onChange(value, position)
                                }
                                filterOption={(input, option) =>
                                    option.props.children
                                        .toLowerCase()
                                        .includes(input.toLowerCase())
                                }
                            >
                                {teachers.map((teacher, index) => {
                                    const remain =
                                        this.state.teachers.findIndex(
                                            _teacher =>
                                                teacher.id ===
                                                    _teacher.teacher &&
                                                _teacher.position !==
                                                    position.position_id
                                        ) === -1
                                    return (
                                        <Option
                                            disabled={teacher.load || !remain}
                                            key={index}
                                            value={teacher.id}
                                        >
                                            {`${teacher.prefix} ${teacher.person_fname} ${teacher.person_lname}`}
                                        </Option>
                                    )
                                })}
                            </Select>
                        </Col>
                    </Row>
                ))}
                <Button key='btn' validate={() => this.validate()} />
            </div>
        )
    }
}
