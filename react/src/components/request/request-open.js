import React from 'react'
import {connect} from 'react-redux'
import Modal from 'antd/lib/modal'
import Icon from 'antd/lib/icon'
import Tag from 'antd/lib/tag'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Collapse from 'antd/lib/collapse'
import RequestProcess from "./request-process";
import Tabs from 'antd/lib/tabs';
import Card from 'antd/lib/card';

const {TabPane} = Tabs

@connect((store) => {
    return {
        lang: store.language.data,
        actions: store.requestData.actions,
        status: store.requestData.status
    }
})
export default class RequestOpen extends React.Component {
    constructor() {
        super()
        this.state = {
            open: false
        }
    }

    open() {
        this.setState({open: true})
    }

    close = () => {
        this.setState({open: false});
    }

    render() {
        const {lang, current, userRequest, status, index, actions} = this.props
        const action = actions[actions.findIndex(action => action.action_id === userRequest.calendar_item.action_id)]
        return status === null ? null : (
            <div>
                <Tag key='btn' onClick={() => this.open()} class='tag-default clickable'>
                    {lang.request_open.btn}
                </Tag>
                <Modal key='md' footer={null} title={`${lang.request_open.head}${action.action_name}`} destroyOnClose
                       visible={this.state.open} onCancel={() => this.close()}>
                    <Tabs defaultActiveKey="1" size={2}>
                        <TabPane tab={lang.request_open.detail} key={1}>
                            {
                                userRequest.advisors.length === 0 ? null :
                                    <div>
                                        <h5>
                                            {lang.request_open.advisor}
                                        </h5>
                                        <Card>
                                            {
                                                userRequest.advisors.map(
                                                    (advisor, index) =>
                                                        <Row key={index}
                                                             class={index === userRequest.advisors.length - 1 ? null : 'margin-bottom-8'}>
                                                            <Col sm={12} span={24}>
                                                                {`${advisor.teacher.prefix} ${advisor.teacher.person_fname} ${advisor.teacher.person_lname}`}
                                                            </Col>
                                                            <Col sm={12} span={24}>
                                                                {advisor.position.position_name}
                                                            </Col>
                                                        </Row>
                                                )
                                            }
                                        </Card>
                                    </div>
                            }
                            {
                                userRequest.defenses.length === 0 ? null :
                                    userRequest.defenses.map(
                                        (defense, index) =>
                                            <Row key={index}>
                                                <Col span={24}>
                                                    <h5>
                                                        {`${lang.request_open.defense}${defense.defense_type.action_name}`}
                                                    </h5>
                                                    <Card>
                                                        <Row>
                                                            <Col sm={12} span={24} class='margin-bottom-8'>
                                                                {lang.request_open.date}
                                                            </Col>
                                                            <Col sm={12} span={24} class='margin-bottom-8'>
                                                                {`${moment(new Date(defense.defense_date)).format('LL')}`}
                                                            </Col>
                                                            <Col sm={12} span={24} class='margin-bottom-8'>
                                                                {lang.request_open.time}
                                                            </Col>
                                                            <Col sm={12} span={24} class='margin-bottom-8'>
                                                                {`${moment(new Date(`${defense.defense_date}T${defense.defense_time_start}`)).format('LT')} - ${moment(new Date(`${defense.defense_date}T${defense.defense_time_end}`)).format('LT')}`}
                                                            </Col>
                                                            <Col sm={12} span={24} class='margin-bottom-8'>
                                                                {lang.request_open.place}
                                                            </Col>
                                                            <Col sm={12} span={24}>
                                                                {defense.room.room_name}
                                                            </Col>
                                                            <Col span={24}>
                                                                <h5 class='text-left'>
                                                                    {lang.request_open.committee}
                                                                </h5>
                                                                <Card class='small-card'>
                                                                    {
                                                                        defense.committees.map(
                                                                            (committee, index) =>
                                                                                <Row key={index}>
                                                                                    <Col sm={12} span={24}>
                                                                                        {`${committee.teacher.prefix} ${committee.teacher.person_fname} ${committee.teacher.person_lname}`}
                                                                                    </Col>
                                                                                    <Col sm={12} span={24}>
                                                                                        {committee.position.position_name}
                                                                                    </Col>
                                                                                </Row>
                                                                        )
                                                                    }
                                                                </Card>
                                                            </Col>
                                                        </Row>
                                                    </Card>
                                                </Col>
                                            </Row>
                                    )
                            }
                        </TabPane>
                        <TabPane tab={lang.request_open.process} key={2}>
                            <RequestProcess index={index} userRequest={userRequest}/>
                        </TabPane>
                    </Tabs>
                </Modal>
            </div>
        )
    }
}