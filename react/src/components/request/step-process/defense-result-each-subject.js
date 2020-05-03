import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'
import InputNumber from 'antd/lib/input-number'
import Input from 'antd/lib/input'
import Checkbox from 'antd/lib/checkbox'
import {
    updateResult,
    updateUserRequest
} from '../../../actions/request/requestData'

const { TextArea } = Input

@connect(store => {
    return {
        lang: store.language.data,
        status: store.requestData.status,
        config: store.main.config,
        currentUser: store.main.currentUser
    }
})
export default class DefenseResultEachSubject extends React.Component {
    change(checked) {
        const {
            dispatch,
            userRequest,
            defense,
            mainIndex,
            index,
            index_,
            config
        } = this.props
        const userRequest_ = Object.assign({}, userRequest, {
            ...userRequest,
            defenses: [
                ...userRequest.defenses.slice(0, index),
                {
                    ...defense,
                    subject: [
                        ...userRequest.defenses[index].subject.slice(0, index_),
                        {
                            ...userRequest.defenses[index].subject[index_],
                            subject_pass: checked,
                            defense_subject_status_id: checked
                                ? config.DEFENSE_STATUS_PASS
                                : config.DEFENSE_STATUS_FAIL
                        },
                        ...userRequest.defenses[index].subject.slice(index_ + 1)
                    ]
                },
                ...userRequest.defenses.slice(index + 1)
            ]
        })
        dispatch(updateUserRequest(mainIndex, userRequest_))
    }

    render() {
        const { subject, edit, config, status, defense } = this.props
        const checked = subject.subject_pass
        const is_default =
            defense.defense_status_id === config.DEFENSE_STATUS_DEFAULT
        const defense_subject_status =
            status[
                status.findIndex(
                    status =>
                        status.status_id === subject.defense_subject_status_id
                )
            ]
        const defense_default_status =
            status[
                status.findIndex(
                    status => status.status_id === config.DEFENSE_STATUS_DEFAULT
                )
            ]
        return (
            <Col span={24}>
                {subject.subject_name}
                {is_default && !edit ? (
                    <Tag class={`tag-default`}>---</Tag>
                ) : (
                    <Tag class={`tag-${defense_subject_status.status_label}`}>
                        {defense_subject_status.status_name}
                    </Tag>
                )}
                {!edit || subject.already_passed ? null : (
                    <Checkbox
                        style={{ marginLeft: 8 }}
                        checked={subject.subject_pass}
                        onChange={ev => this.change(ev.target.checked)}
                    />
                )}
            </Col>
        )
    }
}
