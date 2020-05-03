import React from 'react'
import {connect} from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../../loading';
import Button from '../step/button';
import Tag from 'antd/lib/tag'
import Card from 'antd/lib/card'
import {setProject, updateProject} from "../../../actions/request/requestAdd";
import Icon from 'antd/lib/icon'
import Popover from 'antd/lib/popover'
import Input from 'antd/lib/input'

@connect((store) => {
    return {
        lang: store.language.data,
        calendarItem: store.requestAdd.calendarItem,
        project: store.requestAdd.project
    }
})
export default class Detail extends React.Component {
    constructor(props) {
        super(props)
        const {project} = props
        this.state = {
            loading: false,
            visible: false,
            name_th: project === null ? null : project.project_name_th,
            name_en: project === null ? null : project.project_name_en
        }
    }

    nameThChange(name_th) {
        this.setState({name_th})
    }

    nameEnChange(name_en) {
        this.setState({name_en})
    }

    submit() {
        const {dispatch, container} = this.props
        const {name_th, name_en} = this.state
        if (name_th === null || name_en === null || name_th === '' || name_en === '') {
            alert('>.<')
            return
        }
        this.setState({loading: true})
        dispatch(updateProject(name_th, name_en, response => {
            dispatch(setProject(response))
            this.setState({loading: false, visible: false})
        }))
    }

    validate() {
        const {project, calendarItem} = this.props
        if (calendarItem.action.action_project && project === null) {
            alert('XD')
        }
        return !(calendarItem.action.action_project && project === null)
    }

    render() {
        const {lang, calendarItem, project} = this.props
        const {name_th, name_en, loading, visible} = this.state
        return (
            calendarItem === null ?
                <Loading/> :
                <div>
                    <Row type='flex' class='step' justify='center'>
                        <Col sm={18} span={24}>
                            <h5>
                                {lang.detail.action}
                            </h5>
                            <Card>
                                <Col style={{marginBottom: 16}} span={24}>
                                    <Row>
                                        <Col sm={12} span={24}>
                                            <strong>
                                                {lang.detail.action_name}
                                            </strong>
                                        </Col>
                                        <Col sm={12} span={24}>
                                            {calendarItem.action.action_name}
                                        </Col>
                                    </Row>
                                </Col>
                                <Col style={{marginBottom: 16}} span={24}>
                                    <Row>
                                        <Col sm={12} span={24}>
                                            <strong>
                                                {lang.detail.year}
                                            </strong>
                                        </Col>
                                        <Col sm={12} span={24}>
                                            {calendarItem.calendar.calendar_id}
                                        </Col>
                                    </Row>
                                </Col>
                                <Col style={{marginBottom: 16}} span={24}>
                                    <Row>
                                        <Col sm={12} span={24}>
                                            <strong>
                                                {lang.detail.semester}
                                            </strong>
                                        </Col>
                                        <Col sm={12} span={24}>
                                            {calendarItem.semester.semester_name}
                                        </Col>
                                    </Row>
                                </Col>
                                <Col span={24}>
                                    <Row>
                                        <Col sm={12} span={24}>
                                            <strong>
                                                {lang.detail.level}
                                            </strong>
                                        </Col>
                                        <Col sm={12} span={24}>
                                            {calendarItem.level.level_name}
                                        </Col>
                                    </Row>
                                </Col>
                            </Card>
                        </Col>
                        {
                            !calendarItem.action.action_project ? null :
                                <Col sm={18} span={24}>
                                    <h5>{lang.detail.project}</h5>
                                    <Card>
                                        <Row>
                                            <Col style={{marginBottom: 16}} span={24}>
                                                <Row>
                                                    <Col sm={12} span={24}>
                                                        <strong>
                                                            {lang.detail.project_name_th}
                                                        </strong>
                                                    </Col>
                                                    <Col sm={12} span={24}>
                                                        {project === null ? '---' : project.project_name_th}
                                                    </Col>
                                                </Row>
                                            </Col>
                                            <Col span={24} style={{marginBottom: 16}}>
                                                <Row>
                                                    <Col sm={12} span={24}>
                                                        <strong>
                                                            {lang.detail.project_name_en}
                                                        </strong>
                                                    </Col>
                                                    <Col sm={12} span={24}>
                                                        {project === null ? '---' : project.project_name_en}
                                                    </Col>
                                                </Row>
                                            </Col>
                                            <Col span={24}>
                                                <Popover visible={visible} content={
                                                    <Row gutter={16}>
                                                        <Row style={{marginBottom: 16}} type="flex" justify="center">
                                                            <Col style={{textAlign: 'center'}} sm={12} span={24}>
                                                                {lang.detail.project_name_th}
                                                            </Col>
                                                            <Col sm={12} span={24}>
                                                                <Input value={name_th} size='small'
                                                                       placeholder={lang.detail.project_name_th}
                                                                       onChange={(ev) => this.nameThChange(ev.target.value)}/>
                                                            </Col>
                                                        </Row>
                                                        <Row style={{marginBottom: 16}} type="flex" justify="center">
                                                            <Col style={{textAlign: 'center'}} sm={12} span={24}>
                                                                {lang.detail.project_name_en}
                                                            </Col>
                                                            <Col sm={12} span={24}>
                                                                <Input value={name_en} size='small'
                                                                       placeholder={lang.detail.project_name_en}
                                                                       onChange={(ev) => this.nameEnChange(ev.target.value)}/>
                                                            </Col>
                                                        </Row>
                                                        <Row>
                                                            <Col span={24}>
                                                                <Tag class='clickable tag-big tag-default margin-0'
                                                                     onClick={() => this.submit()}>
                                                                    {
                                                                        loading ?
                                                                            <Icon type="loading"/> : lang.detail.submit
                                                                    }
                                                                </Tag>
                                                            </Col>
                                                        </Row>
                                                    </Row>
                                                } trigger='click'>
                                                    <Tag onClick={() => this.setState({visible: !visible})}
                                                         class='clickable tag-default'>
                                                        {lang.detail.edit}
                                                    </Tag>
                                                </Popover>
                                            </Col>
                                        </Row>
                                    </Card>
                                </Col>
                        }
                    </Row>
                    <Button validate={() => this.validate()}/>
                </div>
        )
    }
}