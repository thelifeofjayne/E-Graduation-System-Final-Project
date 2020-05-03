import React from 'react'
import { connect } from 'react-redux'
import { URL } from './../../config'
import Tag from 'antd/lib/tag'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../loading'
import { getAdvisor } from '../../actions/advisor/advisor-load'
import { setHeader } from '../../actions/main'

@connect(store => {
    return {
        config: store.main.config,
        lang: store.language.data,
        advisors: store.advisorLoad.advisors
    }
})
export default class AdvisorLoad extends React.Component {
    componentDidMount() {
        const { dispatch, lang } = this.props
        dispatch(setHeader(lang.advisor_load.head))
        dispatch(getAdvisor())
    }

    render() {
        const { advisors, lang } = this.props
        return advisors === null ? (
            <Loading />
        ) : (
            <Row>
                <Col span={24}>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>
                                    <Row class='table-row' type='flex'>
                                        <Col
                                            class='text-center table-col'
                                            sm={12}
                                            span={24}
                                        >
                                            {lang.advisor_load.name}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={4}
                                            span={24}
                                        >
                                            {lang.advisor_load.pre_load}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={4}
                                            span={24}
                                        >
                                            {lang.advisor_load.loaded}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={4}
                                            span={24}
                                        >
                                            {lang.advisor_load.total}
                                        </Col>
                                    </Row>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {advisors.map((advisor, index) => (
                                <tr key={index}>
                                    <td>
                                        <Row class='table-row' type='flex'>
                                            <Col
                                                class='text-center table-col'
                                                sm={12}
                                                span={24}
                                            >
                                                {`${advisor.prefix} ${advisor.person_fname} ${advisor.person_lname}`}
                                            </Col>
                                            <Col
                                                class='text-center table-col'
                                                sm={4}
                                                span={24}
                                            >
                                                {advisor.pre_loaded}
                                            </Col>
                                            <Col
                                                class='text-center table-col'
                                                sm={4}
                                                span={24}
                                            >
                                                {advisor.loaded}
                                            </Col>
                                            <Col
                                                class='text-center table-col'
                                                sm={4}
                                                span={24}
                                            >
                                                {`${advisor.pre_loaded +
                                                    advisor.loaded}/${
                                                    advisor.max
                                                }`}
                                            </Col>
                                        </Row>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </Col>
            </Row>
        )
    }
}
