import React from 'react'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Icon from 'antd/lib/icon'

export default class Loading extends React.Component {
    constructor() {
        super()
        this.state = {
            dot: 0
        }
        this.max = 3
    }

    componentDidMount() {
        this.loading()
    }

    loading() {
        setTimeout(() => {
            const { dot } = this.state
            if (this.content)
                this.setState({ dot: dot === this.max ? 0 : dot + 1 })
            this.loading()
        }, 330)
    }

    render() {
        const { dot } = this.state
        const { small } = this.props
        return (
            <Row
                ref={ref => (this.content = ref)}
                type='flex'
                justify='center'
                align='middle'
                style={{
                    height: small ? 'initial' : '50vh',
                    textAlign: 'center'
                }}
            >
                <Col class={small ? null : 'loading'} span={24}>
                    {small ? null : (
                        <Icon style={{ marginRight: 8 }} type='loading' />
                    )}
                    <span>
                        Loading
                        {Array(dot)
                            .fill('.')
                            .map(dot_ => dot_)}
                    </span>
                </Col>
            </Row>
        )
    }
}
