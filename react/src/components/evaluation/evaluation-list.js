import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'
import { URL } from '../../config'
import Loading from '../loading'
import Icon from 'antd/lib/icon'
import {
    activeEvaluation,
    deleteEvaluation,
    getEvaluation
} from '../../actions/evaluation/evaluation-list'
import { setHeader } from '../../actions/main'

@connect(store => {
    return {
        lang: store.language.data,
        evaluations: store.evaluationList.evaluations
    }
})
export default class EvaluationList extends React.Component {
    componentDidMount() {
        const { dispatch, lang } = this.props
        dispatch(setHeader(lang.evaluation_list.head))
        dispatch(getEvaluation())
    }

    active(eval_id) {
        const { dispatch } = this.props
        dispatch(activeEvaluation(eval_id))
    }

    delete(eval_id) {
        const { dispatch, evaluations } = this.props
        dispatch(deleteEvaluation(evaluations, eval_id))
    }

    render() {
        const { evaluations, lang } = this.props
        return evaluations === null ? (
            <Loading />
        ) : (
            <Row>
                <Col span={24} style={{ textAlign: 'right', marginBottom: 8 }}>
                    <Tag
                        class='tag-default tag-medium clickable'
                        onClick={() =>
                            (window.location = `${URL.EGS_BASE}/#/evaluation-add`)
                        }
                    >
                        <Icon style={{ marginRight: 8 }} type='plus-circle-o' />
                        {lang.evaluation_list.add}
                    </Tag>
                </Col>
                <Col span={24}>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>
                                    <Row class='table-row' type='flex'>
                                        <Col
                                            class='text-center table-col'
                                            sm={6}
                                            span={24}
                                        >
                                            {lang.evaluation_list.name}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={6}
                                            span={24}
                                        >
                                            {lang.evaluation_list.active}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={6}
                                            span={24}
                                        >
                                            {lang.evaluation_list.detail}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={6}
                                            span={24}
                                        >
                                            {lang.evaluation_list.delete}
                                        </Col>
                                    </Row>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {evaluations.length === 0 ? (
                                <tr>
                                    <td>
                                        <Row class='table-row' type='flex'>
                                            <Col
                                                class='text-center table-col'
                                                span={24}
                                            >
                                                {lang.nodata}
                                            </Col>
                                        </Row>
                                    </td>
                                </tr>
                            ) : (
                                evaluations.map((evaluation, index) => (
                                    <tr key={index}>
                                        <td>
                                            <Row class='table-row' type='flex'>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    {evaluation.evaluation_name}
                                                </Col>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    {evaluation.evaluation_active ? (
                                                        <Tag class='tag-success'>
                                                            {
                                                                lang
                                                                    .evaluation_list
                                                                    .currnet_active
                                                            }
                                                        </Tag>
                                                    ) : (
                                                        <Tag
                                                            class='clickable tag-default'
                                                            onClick={() =>
                                                                this.active(
                                                                    evaluation.evaluation_id
                                                                )
                                                            }
                                                        >
                                                            {
                                                                lang
                                                                    .evaluation_list
                                                                    .activate
                                                            }
                                                        </Tag>
                                                    )}
                                                </Col>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    <a
                                                        target='_blank'
                                                        href={`${URL.EGS_BASE}/evaluation/find?eval_id=${evaluation.evaluation_id}`}
                                                    >
                                                        <Tag class='tag-default clickable'>
                                                            {
                                                                lang
                                                                    .evaluation_list
                                                                    .detail
                                                            }
                                                        </Tag>
                                                    </a>
                                                </Col>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    <Tag
                                                        class='tag-error clickable'
                                                        onClick={() =>
                                                            this.delete(
                                                                evaluation.evaluation_id
                                                            )
                                                        }
                                                    >
                                                        {
                                                            lang.evaluation_list
                                                                .delete
                                                        }
                                                    </Tag>
                                                </Col>
                                            </Row>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </Col>
            </Row>
        )
    }
}
