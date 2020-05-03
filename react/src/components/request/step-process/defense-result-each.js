import React from 'react'
import {connect} from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'
import InputNumber from 'antd/lib/input-number'
import Input from 'antd/lib/input'
import Checkbox from 'antd/lib/checkbox'
import {updateResult, updateUserRequest} from "../../../actions/request/requestData"
import DefenseResultEachSubject from "./defense-result-each-subject";

const {TextArea} = Input

@connect((store) => {
    return {
        lang: store.language.data,
        status: store.requestData.status,
        config: store.main.config,
        currentUser: store.main.currentUser
    }
})
export default class DefenseResultEach extends React.Component {
    constructor(props) {
        super(props)
        const {defense, config} = props
        this.state = {
            score: defense.defense_score,
            credit: defense.defense_credit,
            comment: defense.defense_comment,
            cond: defense.defense_status_id === config.DEFENSE_STATUS_PASS_CON,
            edit: false,
            disabled: false,
            pass_check: defense.defense_status_id === config.DEFENSE_STATUS_PASS
        }
    }

    update() {
        const {dispatch, userRequest, defense, mainIndex} = this.props
        const {score, credit, comment, cond, pass_check} = this.state
        const post = {score, credit, comment, cond, pass_check}
        this.setState({disabled: true})
        dispatch(updateResult(userRequest, defense, post, response => {
            dispatch(updateUserRequest(mainIndex, response))
            this.setState({disabled: false})
            this.unedit()
        }))
    }

    scoreChange(score) {
        const {cond} = this.state
        const {config} = this.props
        this.setState({score})
        const status_id = score === undefined ?
            config.DEFENSE_STATUS_DEFAULT : score >= config.PASS_DEFENSE_SCORE ? cond ?
                config.DEFENSE_STATUS_PASS_CON : config.DEFENSE_STATUS_PASS : config.DEFENSE_STATUS_FAIL
        this.statusChange(status_id)
    }

    statusChange(status_id) {
        const {dispatch, userRequest, defense, mainIndex, index} = this.props
        const userRequest_ = Object.assign({}, userRequest, {
            ...userRequest,
            defenses: [
                ...userRequest.defenses.slice(0, index),
                {
                    ...defense,
                    defense_status_id: status_id
                },
                ...userRequest.defenses.slice(index + 1)
            ]
        })
        dispatch(updateUserRequest(mainIndex, userRequest_))
    }

    creditChange(credit) {
        this.setState({credit})
    }

    commnetChange(comment) {
        this.setState({comment})
    }

    condChange(cond) {
        const {config} = this.props
        this.setState({cond})
        const status_id = cond ? config.DEFENSE_STATUS_PASS_CON : config.DEFENSE_STATUS_PASS
        this.statusChange(status_id)
    }

    passChange(pass_check) {
        const {config} = this.props
        this.setState({pass_check})
        const status_id = pass_check ? config.DEFENSE_STATUS_PASS : config.DEFENSE_STATUS_FAIL
        this.statusChange(status_id)
    }

    edit() {
        this.setState({edit: true})
    }

    unedit() {
        this.setState({edit: false})
    }

    render() {
        const {lang, defense, status, config, currentUser, notYet, userRequest, mainIndex, index} = this.props
        const {score, credit, comment, edit, cond, disabled, pass_check} = this.state
        const isStaff = currentUser.user_type_id === config.PERSON_STAFF_TYPE
        const isTeacher = currentUser.user_type_id === config.PERSON_TEACHER_TYPE
        const editor = (isStaff || isTeacher) && !notYet
        const pass = score >= config.PASS_DEFENSE_SCORE
        const defense_status = status[status.findIndex(
            status => status.status_id === (!defense.score && edit && defense.defense_status_id === config.DEFENSE_STATUS_DEFAULT ?
                config.DEFENSE_STATUS_FAIL : defense.defense_status_id)
        )]
        const defense_subject = defense.subject.length !== 0
        return (
            <Row>
                <Row style={{marginBottom: 8}}>
                    <h5>
                        {defense.defense_type.action_name}
                    </h5>
                    <Row style={{marginBottom: 8}}>
                        <Col span={6}>
                            {lang.defense_result_each.status}
                        </Col>
                        <Col span={12}>
                            <Tag class={`tag-${defense_status.status_label} margin-0`}>
                                {defense_status.status_name}
                            </Tag>
                        </Col>
                    </Row>
                    {
                        defense_subject ?
                            <Row>
                                <Col span={6}>
                                    {lang.defense_result_each.subject}
                                </Col>
                                <Col span={12}>
                                    <Row>
                                        {
                                            defense.subject.map(
                                                (subject, index_) =>
                                                    <DefenseResultEachSubject
                                                        key={index_} edit={edit} subject={subject}
                                                        index_={index_} index={index} mainIndex={mainIndex}
                                                        userRequest={userRequest} defense={defense}/>
                                            )
                                        }
                                    </Row>
                                </Col>
                            </Row> :
                            <Row>
                                {
                                    defense.score ?
                                        <Row style={{marginBottom: 8}}>
                                            <Col span={6}>
                                                {lang.defense_result_each.score}
                                            </Col>
                                            <Col span={18}>
                                                {
                                                    edit ?
                                                        <InputNumber size='small' min={1} max={100} defaultValue={score}
                                                                     onChange={(value) => this.scoreChange(value)}/> :
                                                        <Tag class='tag-default margin-0'>
                                                            {score === null ? '---' : score}
                                                        </Tag>
                                                }
                                                {
                                                    !edit ? null :
                                                        !defense.defense_cond ? null :
                                                            !pass ? null :
                                                                <Checkbox style={{marginLeft: 8}} checked={cond}
                                                                          onChange={(ev) => this.condChange(ev.target.checked)}>
                                                                    {lang.defense_result_each.cond}
                                                                </Checkbox>
                                                }
                                            </Col>
                                        </Row> :
                                        <Row>
                                            <Col span={6}>
                                                {lang.defense_result_each.pass}
                                            </Col>
                                            <Col span={18}>
                                                <Checkbox onChange={(ev) => this.passChange(ev.target.checked)}
                                                          disabled={!edit} checked={pass_check}/>
                                            </Col>
                                        </Row>
                                }
                                {
                                    !defense.credit ? null :
                                        <Row style={{marginBottom: 8}}>
                                            <Col span={6}>
                                                {lang.defense_result_each.credit}
                                            </Col>
                                            <Col span={18}>
                                                {
                                                    edit ?
                                                        <InputNumber size="small" min={1} max={12} defaultValue={credit}
                                                                     onChange={(value) => this.creditChange(value)}/> :
                                                        <Tag class='tag-default margin-0'>
                                                            {credit === null ? '---' : credit}
                                                        </Tag>
                                                }
                                            </Col>
                                        </Row>
                                }
                                <Row style={{marginBottom: 8}}>
                                    <Col span={6}>
                                        {lang.defense_result_each.comment}
                                    </Col>
                                    <Col span={18}>
                                        {
                                            edit ?
                                                <TextArea
                                                    defaultValue={comment === null ? null : comment.replace(/<br\/>/g, '\n')}
                                                    rows={4} onChange={(ev) => this.commnetChange(ev.target.value)}/> :
                                                <Tag class='tag-default margin-0'
                                                     style={{maxWidth: '100%', height: 'initial'}}>
                                                    {
                                                        comment === null ? '---' :
                                                            defense.defense_comment.split('<br/>').map(
                                                                (comment_, index) =>
                                                                    comment_ === '' ? <br key={index}/> :
                                                                        <div key={index}
                                                                             style={{whiteSpace: 'initial'}}>
                                                                            {comment_}
                                                                        </div>
                                                            )
                                                    }
                                                </Tag>
                                        }
                                    </Col>
                                </Row>
                            </Row>
                    }
                </Row>
                {
                    !editor ? null :
                        <Row style={{marginBottom: 8}}>
                            <Col span={24}>
                                {
                                    edit ?
                                        <Tag class='clickable tag-big tag-success margin-0'
                                             disabled={disabled} onClick={() => this.update()}>
                                            {lang.defense_result_each.insert}
                                        </Tag> :
                                        <Tag class='clickable tag-big tag-default margin-0' onClick={() => this.edit()}>
                                            {lang.defense_result_each.edit}
                                        </Tag>
                                }
                            </Col>
                        </Row>
                }
            </Row>
        )
    }
}