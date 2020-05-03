import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Icon from 'antd/lib/icon'
import { setCurrent, setComponent } from '../../../actions/request/requestAdd'

@connect(store => {
    return {
        lang: store.language.data,
        steps: store.requestAdd.steps,
        current: store.requestAdd.current,
        component: store.requestAdd.component
    }
})
export default class Button extends React.Component {
    previous() {
        const { dispatch, steps, current } = this.props
        let current_ = current - 1
        if (current_ < 0) current_ = 0
        dispatch(setCurrent(current_))
        dispatch(setComponent(steps[current_].step.step_component))
    }

    next() {
        const { dispatch, steps, current, validate } = this.props
        if (validate()) {
            let current_ = current + 1
            if (current_ > steps.length - 1) current_ = steps.length - 1
            dispatch(setCurrent(current_))
            dispatch(setComponent(steps[current_].step.step_component))
        }
    }

    done() {
        const { validate } = this.props
        validate()
    }

    render() {
        const { lang, current, steps, inserting } = this.props
        return (
            <Row type='flex' justify='space-between' style={{ marginTop: 16 }}>
                <Col>
                    {current === 0 ? null : (
                        <button
                            class='btn btn-default btn-3d'
                            onClick={() => this.previous()}
                        >
                            <Icon type='arrow-left' /> {lang.button.previous}
                        </button>
                    )}
                </Col>
                <Col>
                    {current === steps.length - 1 ? (
                        <button
                            class='btn btn-success btn-3d'
                            disabled={inserting}
                            onClick={() => this.done()}
                        >
                            {inserting ? (
                                <Icon type='loading' />
                            ) : (
                                <div>
                                    <Icon type='plus-square-o' />{' '}
                                    {lang.button.insert}
                                </div>
                            )}
                        </button>
                    ) : (
                        <button
                            class='btn btn-blue btn-3d'
                            onClick={() => this.next()}
                        >
                            {lang.button.next} <Icon type='arrow-right' />
                        </button>
                    )}
                </Col>
            </Row>
        )
    }
}
