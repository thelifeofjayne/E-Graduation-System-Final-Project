import React from 'react'
import {connect} from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Card from 'antd/lib/card'
import Tag from 'antd/lib/tag'
import Input from 'antd/lib/input'
import Icon from 'antd/lib/icon'
import JSZip from 'jszip'
import Docxtemplater from 'docxtemplater'
import JSZipUtils from 'jszip-utils'
import {URL} from "../../config";
import {insertEval} from "../../actions/evaluation/evaluation-add";
import {setHeader} from "../../actions/main";

@connect((store) => {
    return {
        lang: store.language.data
    }
})
export default class EvaluationAdd extends React.Component {
    constructor() {
        super()
        this.topic = {
            name: null
        }
        this.group = {
            name: null,
            topic: [this.topic]
        }
        this.state = {
            name: null,
            group: [this.group]
        }
        this.font = 'TH Sarabun New'
    }

    componentDidMount() {
        const {dispatch, lang} = this.props
        dispatch(setHeader(lang.evaluation_add.head))
    }

    addGroup() {
        const {group} = this.state
        this.setState({group: group.concat(this.group)})
    }

    deleteGroup(index) {
        const {group} = this.state
        this.setState({group: group.filter((group, _index) => _index !== index)})
    }

    addTopic(index) {
        const {group} = this.state
        this.setState({
            group: [
                ...group.slice(0, index),
                Object.assign({}, group[index], {
                    ...group[index],
                    topic: group[index].topic.concat(this.topic)
                }),
                ...group.slice(index + 1)
            ]
        })
    }

    deleteTopic(index, index2) {
        const {group} = this.state
        this.setState({
            group: [
                ...group.slice(0, index),
                Object.assign({}, group[index], {
                    topic: group[index].topic.filter((topic, _index) => _index !== index2)
                }),
                ...group.slice(index + 1)
            ]
        })
    }

    groupChange(index, value) {
        const {group} = this.state
        this.setState({
            group: [
                ...group.slice(0, index),
                Object.assign({}, group[index], {name: value}),
                ...group.slice(index + 1)
            ]
        })
    }

    topicChange(index, index2, value) {
        const {group} = this.state
        let group_ = Object.assign({}, group[index], {
            topic: [
                ...group[index].topic.slice(0, index2),
                Object.assign({}, group[index].topic[index2], {name: value}),
                ...group[index].topic.slice(index2 + 1)
            ]
        })
        this.setState({
            group: [
                ...group.slice(0, index),
                group_,
                ...group.slice(index + 1)
            ]
        })
    }

    nameChange(name) {
        this.setState({name})
    }

    arabicToThai(number) {
        switch (number) {
            case 0 :
                return '๐'
            case 1 :
                return '๑'
            case 2 :
                return '๒'
            case 3 :
                return '๓'
            case 4 :
                return '๔'
            case 5 :
                return '๕'
            case 6 :
                return '๖'
            case 7 :
                return '๗'
            case 8 :
                return '๘'
            case 9 :
                return '๙'
        }
    }

    transformBold(string) {
        return `<w:p><w:pPr><w:spacing w:after='0' w:line='240' w:lineRule='auto'/><w:ind w:left='317'/></w:pPr><w:r><w:rPr><w:rFonts w:ascii='${this.font}' w:hAnsi='${this.font}' w:cs='${this.font}'/><w:b/><w:bCs/><w:sz w:val='28'/><w:cs/></w:rPr><w:t>${string}</w:t></w:r></w:p>`
    }

    transform(string) {
        return `<w:p><w:pPr><w:spacing w:after='0' w:line='240' w:lineRule='auto'/><w:ind w:left='317'/></w:pPr><w:r><w:rPr><w:rFonts w:ascii='${this.font}' w:hAnsi='${this.font}' w:cs='${this.font}'/><w:sz w:val='26'/><w:szCs w:val='26'/><w:cs/></w:rPr><w:t>${string}</w:t></w:r></w:p>`
    }

    submit() {
        const {group, name} = this.state
        const {dispatch} = this.props
        let data = {
            gender0: '{gender0}',
            gender1: '{gender1}',
            age0: '{age0}',
            age1: '{age1}',
            age2: '{age2}',
            age3: '{age3}',
            degree0: '{degree0}',
            degree1: '{degree1}',
            branch0: '{branch0}',
            branch1: '{branch1}',
            branch2: '{branch2}',
            year0: '{year0}',
            year1: '{year1}',
            year2: '{year2}',
            year3: '{year3}',
            semester0: '{semester0}',
            semester1: '{semester1}',
            enroll0: '{enroll0}',
            comment: '{comment}',
            startloop: '{#components}',
            endloop: '{/components}'
        }
        let evals = []
        group.map(
            (group_, index) => {
                group_.topic.map(
                    (topic, index2) => {
                        let eval_ = {}
                        if (index2 === 0) {
                            eval_.name = `${this.transformBold(`${this.arabicToThai(index + 1)}. ${group_.name}`)}${this.transform(`(${index2 + 1}) ${topic.name}`)}`
                        } else {
                            eval_.name = `${this.transform(`(${index2 + 1}) ${topic.name}`)}`
                        }
                        new Array(5).fill(0).map((each, index3) => {
                            eval_[`rate${index3}`] = `{rate${index}${index2}${index3}}`
                        })
                        evals.push(eval_)
                    }
                )
            })
        data = {...data, evals}

        JSZipUtils.getBinaryContent(`${URL.BASE}/web_egs/docx/eval_template.docx`, (error, content) => {
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
            let file = new File([out, out], "eval_template.docx")
            dispatch(insertEval(group, file, name, response => {
                window.location = URL.EVALUATION.EVALUATION_LIST.MAIN.LINK
            }))
        })
    }

    render() {
        const {lang} = this.props
        const {group, name} = this.state
        return (
            <Row>
                <h5>
                    {lang.evaluation_add.name}
                </h5>
                <Input class='margin-bottom-16' value={name}
                       onChange={(ev) => this.nameChange(ev.target.value)}
                       placeholder='name'/>
                {
                    group.map(
                        (group, index) =>
                            <Col class='margin-bottom-16' key={index}>
                                <Card class='small-card'>
                                    <h5 style={{textAlign: 'left'}}>
                                        {lang.evaluation_add.topic_group}
                                    </h5>
                                    <Row>
                                        <Col sm={21} span={18}>
                                            <Input class='margin-bottom-16' value={group.name}
                                                   onChange={(ev) => this.groupChange(index, ev.target.value)}
                                                   placeholder='group'/>
                                        </Col>
                                        <Col sm={3} span={6}>
                                            <Tag class='clickable tag-medium tag-error'
                                                 onClick={() => this.deleteGroup(index)}>
                                                {lang.evaluation_add.delete}
                                            </Tag>
                                        </Col>
                                    </Row>
                                    <h5 style={{textAlign: 'left'}}>
                                        {lang.evaluation_add.topic}
                                    </h5>
                                    <Card class='small-card'>
                                        {
                                            group.topic.map(
                                                (topic, index2) =>
                                                    <Row key={index2}>
                                                        <Col sm={21} span={18}>
                                                            <Input class='margin-bottom-16' value={topic.name}
                                                                   onChange={(ev) => this.topicChange(index, index2, ev.target.value)}
                                                                   placeholder='topic'/>
                                                        </Col>
                                                        <Col sm={3} span={6}>
                                                            <Tag class='clickable tag-medium tag-error'
                                                                 onClick={() => this.deleteTopic(index, index2)}>
                                                                {lang.evaluation_add.delete}
                                                            </Tag>
                                                        </Col>
                                                    </Row>
                                            )
                                        }
                                        <Tag class='clickable tag-medium tag-empty margin-0'
                                             onClick={() => this.addTopic(index)}
                                             style={{width: '100%', textAlign: 'center'}}>
                                            <Icon type="plus"/> {lang.evaluation_add.topic_add}
                                        </Tag>
                                    </Card>
                                </Card>
                            </Col>
                    )
                }
                <Col style={{textAlign: 'center', marginBottom: 16}}>
                    <Tag class='clickable tag-medium tag-empty margin-0'
                         style={{width: '100%'}} onClick={() => this.addGroup()}>
                        <Icon type="plus"/> {lang.evaluation_add.topic_group_add}
                    </Tag>
                </Col>
                <Col>
                    <Tag class='clickable tag-big tag-success margin-0' onClick={() => this.submit()}>
                        {lang.evaluation_add.submit}
                    </Tag>
                </Col>
            </Row>
        )
    }
}