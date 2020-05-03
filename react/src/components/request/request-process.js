import React from 'react'
import { connect } from 'react-redux'
import Steps from 'antd/lib/steps'
import { getStep, unmount } from '../../actions/request/requestData'
import PetBefore from './step-process/pet-before'
import DocBefore from './step-process/doc-before'
import DefenseResult from './step-process/defense-result'
import DocAfter from './step-process/doc-after'
import PetAfter from './step-process/pet-after'
import Final from './step-process/final'
import Icon from 'antd/lib/icon'
import Loading from '../loading'

const Step = Steps.Step

@connect(store => {
    return {
        lang: store.language.data
    }
})
export default class RequestProcess extends React.Component {
    constructor() {
        super()
        this.component = {
            PetBefore,
            DocBefore,
            DefenseResult,
            PetAfter,
            DocAfter,
            Final
        }
    }

    componentWillUnmount() {
        const { dispatch } = this.props
    }

    componentDidMount() {
        const { dispatch, userRequest, current } = this.props
    }

    render() {
        const { lang, userRequest, index } = this.props
        const steps = userRequest.step
        const currentStep =
            steps === null
                ? null
                : steps[
                      steps.findIndex(
                          step => step.step.step_id === userRequest.current_step
                      )
                  ]
        return (
            <Steps
                direction='vertical'
                size='small'
                current={currentStep.action_step_index}
            >
                {steps.map((step, index_) => {
                    const Component = this.component[step.step.step_component]
                    const notYet =
                        step.action_step_index > currentStep.action_step_index
                    return (
                        <Step
                            key={index_}
                            style={{ opacity: !notYet ? 1 : 0.5 }}
                            icon={<Icon type={step.step.step_icon} />}
                            title={<strong>{step.step.step_name}</strong>}
                            description={
                                <Component
                                    index={index}
                                    notYet={notYet}
                                    userRequest={userRequest}
                                />
                            }
                        />
                    )
                })}
            </Steps>
        )
    }
}
